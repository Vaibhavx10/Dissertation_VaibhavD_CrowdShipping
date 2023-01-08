<?php
require 'vendor/autoload.php';


Flight::route('/loginaction', function() {
    require 'config.php'; 

    $db->where("username", $_REQUEST['email']);
    $db->where("password", $_REQUEST['password']);
    $user = $db->getOne("users");
    if (isset($user)) {
        $_SESSION['user'] = $user;
        $output['status'] = 1 ;
    } else {
        $output['status'] = 0 ;
    }
    echo json_encode($output);

});

Flight::route('/logout', function() {
    require 'config.php';
    //session_abort();
    session_destroy();
    header('Location: ' . $base_url);
    exit();
});
Flight::route('/', function () {
    require 'config.php'; 
    if(isset($_SESSION['user'])){
        header('Location: ' . $base_url.'dashboard');
        exit();
    }else{
        require_once "view/home/index.php";
    }
});

Flight::route('/dashboard', function () {
    require 'config.php'; 
    check_login();
    $type = $_SESSION['user']['type'];
    $db->rawQuery("UPDATE user set subscription_status = 'E' WHERE curdate() > subscription_end_date");

    if($type=='user'){
        $id = $_SESSION['user']['refId'];
        $db->where('user_id ',$id);
        $pobox = $db->get('pobox');

        $db->where('a.user_id',$id);
        $db->where('order_status <> "D"');
        $orders = $db->get('orders as a inner join pobox as p on p.PObox_id = a.PObox_id',null,'a.*');
        
        $db->where('user_id',$_SESSION['user']['refId']);
        $user = $db->get('`user` as a LEFT JOIN subscription_plan as b on b.subscription_plan_id = a.subscription_plan_id',null,"IFNULL(b.monthly_rate,'') as monthly_rate,IFNULL(b.plan_name,'Nil') as plan_name,a.*,DATE_FORMAT(subscription_start_date,'%d-%m-%Y') as stdate,DATE_FORMAT(subscription_end_date,'%d-%m-%Y') as endate,max_transactions,DATEDIFF(subscription_end_date,curdate()) as remain");
        
        $db->where('a.user_id',$_SESSION['user']['refId']);
        $db->where('a.createdDate between b.subscription_start_date and b.subscription_end_date');
        $data = $db->get('orders as a inner join user as b on a.user_id  = b.user_id  inner join subscription_plan as c on c.subscription_plan_id = b.subscription_plan_id',null,'b.subscription_plan_id,max_transactions');
        $used = sizeof($data);

        $db->where('user_id',$_SESSION['user']['refId']);
        $net_transaction = $db->get('net_transaction as a ',null,'SUM(net_truck_miles) as net_truck_miles ,sum(net_co2_saved) as net_co2_saved');
        
        $xml=simplexml_load_file("emissions.XML");
        foreach($xml->children() as $child)
        {
            if($child->getName() == 'fuelequivalent'){
                $fuelequivalent = (float) $child->value;

            }
        }
        require_once "view/home/userdashboard.php";
    }
    if($type=='driver'){
        $db->where('driver_id',$_SESSION['user']['refId']);
        $driver_kyc = $db->get('driver_kyc');

        $db->where('driver_id',$_SESSION['user']['refId']);
        $driver_payout = $db->get('driver_payout',null,'sum(CASE WHEN payout_status = 1 then payout else 0 end) as payed,sum(CASE WHEN payout_status = 0 then payout else 0 end) as pending');
        require_once "view/home/driverdashboard.php";
    }

    if($type=='admin'){
        $user = $db->get('`user` as a',null,"COUNT(CASE when user_active_flag = 1 then user_id else null end ) as activeuser,COUNT(CASE when user_active_flag = 1 and subscription_status = 'A' then user_id else null end) activesubscriptionusers,COUNT(CASE when user_active_flag = 1 and subscription_status = 'T' then user_id else null end) consumedsubscriptionusers,COUNT(CASE when user_active_flag = 1 and subscription_status = 'T' then user_id else null end) expiredsubscriptionusers,COUNT(CASE when user_active_flag = 1 and (subscription_status = 'N' or subscription_status ='' ) then user_id else null end) nosubscriptionusers ");
        $driver = $db->get('`driver` as a',null,'COUNT(case when driver_active_flag = 1 then driver_id else null end) as cnt ');
        $net_transaction = $db->get('net_transaction as a ',null,'SUM(net_truck_miles) as net_truck_miles ,sum(net_co2_saved) as net_co2_saved');
        
        $xml=simplexml_load_file("emissions.XML");
        foreach($xml->children() as $child)
        {
            if($child->getName() == 'fuelequivalent'){
                $fuelequivalent = (float) $child->value;

            }
        }
        require_once "view/home/admindashboard.php";
    }
});

Flight::route('/clickandcollect', function () {
    require 'config.php'; 
    check_login();
    $db->rawQuery("UPDATE user set subscription_status = 'E' WHERE curdate() > subscription_end_date");
    $db->where('user_id',$_SESSION['user']['refId']);
    $id = $_SESSION['user']['refId'];
    $db->where('user_id ',$id);
    $pobox = $db->get('pobox');
    $user = $db->getOne('`user` as a LEFT JOIN subscription_plan as b on b.subscription_plan_id = a.subscription_plan_id',null,"IFNULL(b.monthly_rate,'') as monthly_rate,IFNULL(b.plan_name,'Nil') as plan_name,a.*");
    require_once "view/home/clickandcollect.php";
});


Flight::route('/readxml', function () {
    require 'config.php'; 
    $xml=simplexml_load_file("emissions.XML");
    foreach($xml->children() as $child)
     {
         if($child->getName() == 'perunitmiles'){
            $data['perunitmiles'] = $child->value;

         }
     }

     print(date('Y-m-01'));
    });

function update_net_transaction($db,$user,$distance){
    $xml=simplexml_load_file("emissions.XML");
    foreach($xml->children() as $child)
     {
         if($child->getName() == 'perunitmiles'){
            $data['perunitmiles'] = (float) $child->value;

         }
     }

    $perunitmiles =  $data['perunitmiles'];
    $year = date('Y');
    $month = date('m');
    $db->where('user_id',$user);
    $db->where('year',$year);
    $db->where('month',$month);
    $net_transaction = $db->get('net_transaction',null,'*');
    if(sizeof($net_transaction)  < 1 ){
        $data = array(
            'user_id'=>$user,
            'month'=>$month,
            'year'=>$year,
            'net_transactions'=>1,
            'net_truck_miles'=>$distance,
            'net_co2_saved'=>(float)$distance * (float) $perunitmiles
        );
        $db->insert('net_transaction',$data);
    }else{
        $net_transactions = $net_transaction[0]['net_transactions'];
        $net_transactions = $net_transactions + 1;
        $net_truck_miles =  $net_transaction[0]['net_truck_miles'];
        $net_truck_miles = (float) $net_truck_miles + (float) $distance;
        $net_co2_saved = (float) $net_truck_miles * (float) $perunitmiles;
        $data = array(
            'net_transactions'=>$net_transactions,
            'net_truck_miles'=>$net_truck_miles,
            'net_co2_saved'=>$net_co2_saved
        );
        $db->where('user_id',$user);
        $db->where('year',$year);
        $db->where('month',$month);
        $db->update('net_transaction',$data);
    }
}
function update_user_subscription($db){
    $id = $_SESSION['user']['refId'];
    $db->where('a.user_id',$id);
    $db->where('a.createdDate between b.subscription_start_date and b.subscription_end_date');
    $data = $db->get('orders as a inner join user as b on a.user_id  = b.user_id  inner join subscription_plan as c on c.subscription_plan_id = b.subscription_plan_id',null,'b.subscription_plan_id,max_transactions');
    if(sizeof($data) > 0 ){
        $max_transactions = $data[0]['max_transactions'];
        if($max_transactions <= sizeof($data)){
            $datas = array(
                'subscription_status'=>'T'
            );
            $db->where('user_id',$id);
            $db->update('user',$datas);
        }
    }
}

function getthresholds(){
    $xml=simplexml_load_file("driverthresholds.XML");
    foreach($xml->children() as $child)
    {
        if($child->getName() == 'collectionthreshold'){
            $data['collectionthreshold'] = (float) $child->value[0];
        }
        if($child->getName() == 'deliverythreshold'){
            $data['deliverythreshold'] = (float) $child->value;
        }
    }
    return $data;
}


    Flight::route('/profile', function () {
    require 'config.php'; 
    check_login();
    $db->rawQuery("UPDATE user set subscription_status = 'E' WHERE curdate() > subscription_end_date");

    if($_SESSION['user']['type'] == 'user'){
        $subscription = $db->get('`subscription_plan`  as a',null,'a.*,curdate() as date ,DATE_ADD(curdate(), INTERVAL +30 DAY) as expiry_date');
        $db->where('user_id',$_SESSION['user']['refId']);
        $user = $db->getOne('`user` as a LEFT JOIN subscription_plan as b on b.subscription_plan_id = a.subscription_plan_id',null,"IFNULL(b.monthly_rate,'') as monthly_rate,IFNULL(b.plan_name,'Nil') as plan_name,a.*");
        require_once "view/home/view_user_profile.php";
    }else if($_SESSION['user']['type'] == 'driver'){
        $db->where('a.driver_id',$_SESSION['user']['refId']);
        $user = $db->getOne('`driver` as a INNER JOIN driver_kyc as b on a.driver_id = b.driver_id',null,'a.*,b.kyc_status,b.kyc_doc');
        require_once "view/home/view_driver_profile.php";
    }
});

Flight::route('/subscription', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_subscription.php";
});

Flight::route('/payout', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/payout.php";
});

Flight::route('/user/net_transaction', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/net_transaction.php";
});

Flight::route('/user/net_transaction_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $sess_user = $_SESSION['user'];
    $user_id = $sess_user['refId'];
    $arraycolums = array('month');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy("CAST(a.year as int),CAST(a.month as int)", "desc");
    } else {
        $db->orderBy("CAST(a.year as int),CAST(a.month as int)", "desc");
    }
    $db->where('user_id',$sess_user['refId']);
    $assets = $db->withTotalCount()->get(" `net_transaction` as a", Array($skip, $start), '*');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['month'];
        $row[] = $item['year'];
        $row[] = $item['net_transactions'];
        $row[] = $item['net_truck_miles'];
        $row[] = $item['net_co2_saved'];
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});



Flight::route('/driver/payout/pending', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/pendingpayout.php";
});

Flight::route('/driver/payout/paid', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/paidpayout.php";
});

Flight::route('/driver/list_payout', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $sess_user = $_SESSION['user'];
    $user_id = $sess_user['refId'];
    $arraycolums = array('driver_name','month','year');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy("CAST(a.year as int),CAST(a.month as int)", "desc");
    } else {
        $db->orderBy("CAST(a.year as int),CAST(a.month as int)", "desc");
    }
    $db->groupBy(' a.driver_id,month,year ');
    $assets = $db->withTotalCount()->get(" `driver_payout` as a INNER JOIN driver as b on a.driver_id = b.driver_id and b.driver_id = ".$sess_user['refId']."  and payout_status = ".$_POST['status']." ", Array($skip, $start), 'b.driver_id,b.driver_name,month,year,payout_status,sum(payout) as payout,payout_id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['payout'];
        $row[] = $item['month'];
        $row[] = $item['year'];
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});


Flight::route('/list_payout', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $sess_user = $_SESSION['user'];
    $user_id = $sess_user['refId'];
    $arraycolums = array('driver_name','year','month');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy("CAST(a.year as int),CAST(a.month as int)", "desc");
    } else {
        $db->orderBy("CAST(a.year as int),CAST(a.month as int)", "desc");
    }
    $db->groupBy(' a.driver_id,month,year ');
    $assets = $db->withTotalCount()->get(" `driver_payout` as a INNER JOIN driver as b on a.driver_id = b.driver_id ", Array($skip, $start), 'b.driver_id,b.driver_name,month,year,payout_status,sum(payout) as payout,payout_id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['driver_name'];
        $row[] = $item['year'];
        $row[] = $item['month'];
        $row[] = $item['payout'];
        if((float) $item['payout_status'] < 1){
           if((int) $item['month'] == (int) date('m') &&  (int) $item['year'] == (int) date('Y')){
                $row[] =  'Not Avalilable';  
           }else{
                $row[] = '<div class="switch"><label><input attr-driver_id= '.$item['driver_id'].' attr-month= '.$item['month'].' attr-year= '.$item['year'].' type="checkbox" class = "changeOne"   onclick=""><span class="lever switch-col-green"></span></label></div>';
           }
        }else{
            $row[] = '<div class="switch"><label><input checked disabled attr-id= '.$item['payout_id'].' type="checkbox" class = ""   onclick=""><span class="lever switch-col-green"></span></label></div>';

        }
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});


Flight::route('/warehouse', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_warehouse.php";
});

Flight::route('/calculatepayout', function () {
    require 'config.php'; 
    check_login();
    $date = date('Y-m-01');
    //$data = $db->get('`order_transit` as a INNER JOIN driver as b on a.driver_id = b.driver_id INNER JOIN orders as c on c.order_id = a.order_id and a.transit_status = "D" and c.createdDate < "'.$date.'" ',null,'a.*,b.driver_name,c.createdDate, year(createddate),month(createddate) FROM `order_transit` as a INNER JOIN driver as b on a.driver_id = b.driver_id');
    $data = $db->get('(SELECT a.*,b.driver_name,c.createdDate, year(createddate) as year,month(createddate) as month FROM `order_transit` as a INNER JOIN driver as b on a.driver_id = b.driver_id INNER JOIN orders as c on c.order_id = a.order_id and a.transit_status = "D" and c.createdDate < "'.$date.'" ) asa ',null,'*');
    foreach($data as $item){
        $db->where('transit_id',$item['transit_id']);
        $driver_payout = $db->get('driver_payout');
        if(sizeof($driver_payout)  < 1 ){
            $ins_array = array(
                'transit_id'=>$item['transit_id'],
                'driver_id'=>$item['driver_id'],
                'payout_status'=>0,
                'year'=>$item['year'],
                'month'=>$item['month'],
                'payout'=>$item['transit_pay']
            );
            $db->insert('driver_payout',$ins_array);
        }else{

        }
    }
    
});

Flight::route('/order/getchartdetails', function () {
    require 'config.php'; 
    check_login();
    $db->groupBy('year,month');
    $db->where('payout_status',1);
    $data = $db->get('driver_payout',null,'sum(payout) as payout,year,month,monthname(concat(year,"-",month,"-",12)) as monthname');
    echo json_encode($data);

});

Flight::route('/orders/manage/collection', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_orders_collection.php";
});

Flight::route('/orders/manage/transist', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_orders_transist.php";
});

Flight::route('/orders_collection_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('source_address');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.collection_id", "desc");
    }
    $assets = $db->withTotalCount()->get("`collection` as a INNER JOIN orders as b on a.collection_id = b.collection_id  and (order_status = 'WC' or order_status = 'TC' or order_status='C' or order_status ='CFW' )  inner join order_transit on order_transit.order_id = b.order_id and transit_status = 'A' INNER JOIN user as c on c.user_id = a.user_id INNER JOIN order_transit as d on d.order_id = b.order_id INNER JOIN pobox as e on e.PObox_id = b.PObox_id left JOIN driver as f on f.driver_id = d.driver_id ", Array($skip, $start), 'f.driver_name,f.driver_email,b.order_id,a.collection_id,source_address,collection_date,collection_time,order_status,collection_time,source_col_ref,order_status,collection_flag,delivery_address,c.user_name');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['order_id'];
        $row[] = $item['source_address'];
        $row[] = $item['collection_time'];
        $row[] = $item['source_col_ref'];
        if($item['order_status'] =='WC' || $item['order_status'] =='TC' ){
            $row[] = '<div class="switch"><label><input attr-id= '.$item['order_id'].' type="checkbox" class = "changeOne"   onclick=""><span class="lever switch-col-green"></span></label></div>';
        }else if($item['order_status'] =='C' || $item['order_status'] =='CFW'){
            $row[] = '<div class="switch"><label><input attr-id= '.$item['order_id'].'  disabled checked type="checkbox"  class = "changeOne"  onclick=""><span class="lever switch-col-green"></span></label></div>';
        }else{
            $row[] = '';
        }

        if($item['order_status'] =='WC' || $item['order_status'] =='TC' || $item['order_status'] =='C' || $item['order_status'] =='CFW' ){
            $row[] = '<div class="switch"><label><input attr-id= '.$item['order_id'].' type="checkbox" class = "changeTwo"  onclick=""><span class="lever switch-col-green"></span></label></div>';
        }else if($item['order_status'] =='T' || $item['order_status'] =='D'){
            $row[] = '<div class="switch"><label><input attr-id= '.$item['order_id'].' checked disabled type="checkbox"  class = "changeTwo" onclick=""><span class="lever switch-col-green"></span></label></div>';
        }else{
            $row[] = '';
        }
        
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});

Flight::route('/orders_transist_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('source_address');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.collection_id", "desc");
    }
    $assets = $db->withTotalCount()->get("`collection` as a INNER JOIN orders as b on a.collection_id = b.collection_id  and b.order_status = 'T'  inner join order_transit d on d.order_id = b.order_id and transit_status = 'T' INNER JOIN user as c on c.user_id = a.user_id  INNER JOIN pobox as e on e.PObox_id = b.PObox_id left JOIN driver as f on f.driver_id = d.driver_id ", Array($skip, $start), 'f.driver_name,f.driver_email,b.order_id,a.collection_id,source_address,collection_date,collection_time,collection_time,source_col_ref,order_status,collection_flag,delivery_address,c.user_name,delivery_start_time,delivery_end_time,transit_status');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['order_id'];
        $row[] = $item['source_address'];
       
        $act = '';
        $row[] = $item['delivery_start_time'].' - '.$item['delivery_end_time'];
        if($item['transit_status'] !='D' ){
            $row[] = '<div class="switch"><label><input attr-id= '.$item['order_id'].' type="checkbox" class = "changeThree"   onclick=""><span class="lever switch-col-green"></span></label></div>';
        }else {
            $row[] = '<div class="switch"><label><input attr-id= '.$item['order_id'].'  disabled checked type="checkbox"  class = "changeOne"  onclick=""><span class="lever switch-col-green"></span></label></div>';
        }
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});

Flight::route('/driver/transit_history', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/transit_history.php";
});

Flight::route('/transit_history_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('b.order_id','transit_status','a.user_id','b.PObox_id','delivery_eir','source_eir','transit_distance');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.collection_id", "desc");
    }
    $db->where('f.driver_id',$_SESSION['user']['refId']);
    $assets = $db->withTotalCount()->get("`collection` as a INNER JOIN orders as b on a.collection_id = b.collection_id  inner join order_transit d on d.order_id = b.order_id INNER JOIN user as c on c.user_id = a.user_id  INNER JOIN pobox as e on e.PObox_id = b.PObox_id inner JOIN driver as f on f.driver_id = d.driver_id and f.driver_id = ".$_SESSION['user']['refId']." ", Array($skip, $start), 'source_eir,delivery_eir,b.user_id,d.driver_id,f.driver_name,transit_distance,f.driver_email,b.order_id,a.collection_id,source_address,collection_date,collection_time,source_col_ref,order_status,collection_flag,delivery_address,c.user_name,e.PObox_id,transit_status');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['order_id'];
        $row[] = $item['source_eir'];
        $row[] = $item['delivery_eir'];
        $act = $item['transit_status'];
        if($item['transit_status'] =='A'){
            $act = 'Allocated';
        }
        if($item['transit_status'] =='D'){
            $act = 'Delivered';
        }
        if($item['transit_status'] =='UA'){
            $act = 'Unallocated';
        }
        if($item['transit_status'] =='C'){
            $act = 'Collected';
        }

        if($item['transit_status'] =='T'){
            $act = 'Transit';
        }
        $row[] = $act . ' ('.$item['transit_status'].') ' ;
        $row[] = $item['transit_distance'];
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});



Flight::route('/orders/pending', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_pending_orders.php";
});

Flight::route('/pending_orders_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('a.user_id','a.PObox_id');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.collection_id", "desc");
    }
    $db->where('order_status in("WC","WI","TC") ');
    $assets = $db->withTotalCount()->get("`collection` as a INNER JOIN orders as b on a.collection_id = b.collection_id  inner join order_transit d on d.order_id = b.order_id INNER JOIN user as c on c.user_id = a.user_id  INNER JOIN pobox as e on e.PObox_id = b.PObox_id left JOIN driver as f on f.driver_id = d.driver_id ", Array($skip, $start), 'b.user_id,d.driver_id,f.driver_name,transit_distance,f.driver_email,b.order_id,a.collection_id,source_address,collection_date,collection_time,source_col_ref,order_status,collection_flag,delivery_address,c.user_name,e.PObox_id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['user_id'];
        $row[] = $item['PObox_id'];
        $row[] = $item['driver_id'];
        if($item['order_status'] =='WC'){
            $act = 'Waiting For Collection';
        }
        if($item['order_status'] =='C'){
            $act = 'Order  Collected';
        }
        if($item['order_status'] =='T'){
            $act = 'Transit';
        }

        if($item['order_status'] =='D'){
            $act = 'Delivered';
        }
        $row[] = $act;
        
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});

Flight::route('/orders/manage', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_orders.php";
});



Flight::route('/orders_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('transit_distance','b.order_id','order_status','c.user_name','driver_name','delivery_address','source_address','e.PObox_id');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.collection_id", "desc");
    }
    $assets = $db->withTotalCount()->get("`collection` as a INNER JOIN orders as b on a.collection_id = b.collection_id and b.user_id = ".$_SESSION['user']['refId']."  inner join order_transit d on d.order_id = b.order_id INNER JOIN user as c on c.user_id = a.user_id  INNER JOIN pobox as e on e.PObox_id = b.PObox_id left JOIN driver as f on f.driver_id = d.driver_id ", Array($skip, $start), 'f.driver_name,transit_distance,f.driver_email,b.order_id,a.collection_id,source_address,collection_date,collection_time,source_col_ref,order_status,collection_flag,delivery_address,c.user_name,e.PObox_id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['order_id'];
        $row[] = $item['PObox_id'];
        if($item['order_status'] =='WC'){
            $act = 'Waiting For Collection';
        }
        if($item['order_status'] =='C'){
            $act = 'Order  Collected';
        }
        if($item['order_status'] =='T'){
            $act = 'Transit';
        }

        if($item['order_status'] =='D'){
            $act = 'Delivered';
        }
        $act = $act .' ('.$item['order_status'].')';
        $row[] = $act;
        $row[] = $item['transit_distance'];
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});


Flight::route('/driver/rate', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_driver_rate.php";
});

Flight::route('/driver_rate_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('rate');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.rate_id", "desc");
    }
    $assets = $db->withTotalCount()->get("driver_pay_rate as a ", Array($skip, $start), 'a.*,rate_id as id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['rate'];
        $act = '<a href="' . $base_url . 'driver/rate/create/' . $item['id'] . '"  tp="confirm" id="' . $item['id'] . '" data-toggle="tooltip" title="Change Status" type="button" class="btn btn-primary pay" data-target="#statusmodal"><i class="ti-pencil"></i></a>';

        $row[] = $act;
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});



Flight::route('/warehouse_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('wh_address', 'wh_eir');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.wh_id", "desc");
    }
    $assets = $db->withTotalCount()->get(" warehouse as a ", Array($skip, $start), 'a.*,wh_id as id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['wh_address'];
        $row[] = $item['wh_eir'];
        $act = '<a href="' . $base_url . 'warehouse/create/' . $item['id'] . '"  tp="confirm" id="' . $item['id'] . '" data-toggle="tooltip" title="Change Status" type="button" class="btn btn-primary pay" data-target="#statusmodal"><i class="ti-pencil"></i></a>';

        $row[] = $act;
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});

Flight::route('/save_order', function () {
    require 'config.php'; 
    $source_col_ref = $_POST['source_col_ref'];
    $source_address = $_POST['source_address'];
    $source_eir = $_POST['source_eir'];
    $PObox_id = $_POST['PObox_id'];
    $db->where('PObox_id',$PObox_id);
    $pobox = $db->getOne('pobox');
    $route_po_box_pc = str_replace("+","%2b",$pobox['delivery_pc']);
    
    $collection_date = $_POST['collection_date'];
    $collection_time = $_POST['collection_time'];
    $delivery_start_time = $_POST['delivery_start_time'];
    $delivery_end_time = $_POST['delivery_end_time'];
    $source_pc = '';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=".$source_eir."&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $data = json_decode($output);
    if(isset($data->status) ){ 
        if($data->status == 'OK'){
            $plus_code = $data->plus_code;
            $route_source_pc = $plus_code->global_code;
            $source_pc = $route_source_pc;
        }
    }
    $collection_data = array(
        'source_address'=>$source_address,
        'source_pc'=>$source_pc,
        'collection_date'=>$collection_date,
        'collection_time'=>$collection_time,
        'source_eir'=>$source_eir,
        'source_col_ref'=>$source_col_ref,
        'user_id'=>$_SESSION['user']['refId']
    );
    $errorcode = '';
    $order_data = array(
        'user_id'=>$_SESSION['user']['refId'],
        'collection_flag'=>'T',
        'PObox_id'=>$PObox_id,
		'createdDate'=> date('Y-m-d'),
        'delivery_end_time'=>$delivery_end_time,
        'delivery_start_time'=>$delivery_start_time
    );
    $iscontinue = 1;
    $is_driver_exist = 0;
    $is_pobox_driver_exist = 0;
    $driver = $db->get('(SELECT * FROM `driver` WHERE "'.$collection_time.'" BETWEEN service_start_time and service_end_time) a ',null,'*');
    //$driver_threshold = 1000;
    //$destination_threshold = 1000;

    $threshold_data = getthresholds();
    $driver_threshold = $threshold_data['collectionthreshold'];
    $destination_threshold = $threshold_data['deliverythreshold'];


    if(sizeof($driver) < 1 ){
        $errorcode = 202;
        $iscontinue = 0;
    }else if($source_pc != ''){
        $len = sizeof($driver);
        for($i=0;$i<$len;$i++){
            $driver[$i]['source_distance_status'] = 0 ;
            $driver[$i]['destination_distance_status'] = 0 ;
            $driver[$i]['source_distance_pobox_status'] = 0 ;
            $curl = curl_init();
            $source_api = str_replace("+","%2b",$source_pc);
            $route_source_pc = str_replace("+","%2b",$driver[$i]['route_source_pc']);
            $route_destination_pc = str_replace("+","%2b",$driver[$i]['route_destination_pc']);
            $driver[$i]['source_distance'] = -1;
            $driver[$i]['destination_distance'] = -1;
            curl_setopt($curl, CURLOPT_URL, "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$source_api."&destinations=".$route_source_pc."&key=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($curl);
            $data = json_decode($output);
            if($data->status == 'OK'){
                $row = $data->rows[0];
                $elements = $row->elements[0];
                $elements_text = $elements->distance->text;
                $driver[$i]['source_distance_text'] = $elements_text;
                $elements_text = explode(" ",$elements_text);
                $driver[$i]['source_distance'] = $elements_text[0];
                if((float) $elements_text[0] <= (float) $driver_threshold){
                    $is_driver_exist = 1;
                    $driver[$i]['source_distance_status'] = 1 ;
                    $driver[$i]['source_distance_pobox_status'] = 0;
                    curl_close($curl);

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$route_source_pc."&destinations=".$route_po_box_pc."&key=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $output_sub = curl_exec($curl);
                    $data_sub = json_decode($output_sub);
                    if($data_sub->status == 'OK'){
                        $row_sub = $data_sub->rows[0];
                        $elements_sub = $row_sub->elements[0];
                        $elements_text_sub = $elements_sub->distance->text;
                        $elements_text_sub = explode(" ",$elements_text_sub);
                        $driver[$i]['route_source_pc'] = $route_source_pc;
                        if((float) $elements_text_sub[0] <= (float) $destination_threshold){
                            $driver[$i]['source_distance_pobox_status'] = 1;
                            $is_pobox_driver_exist = 1;
                            $driver[$i]['source_distance_pobox_km'] = $elements_text_sub[0];
                        }else{
                            $driver[$i]['source_distance_pobox_status'] = 0;
                        }
                    }
                    curl_close($curl);






                }else{
                    $driver[$i]['source_distance_status'] = 0 ;
                }

            }

            $curl = curl_init();
            $source_api = str_replace("+","%2b",$source_pc);
            $route_source_pc = str_replace("+","%2b",$driver[$i]['route_source_pc']);
            $route_destination_pc = str_replace("+","%2b",$driver[$i]['route_destination_pc']);
            curl_setopt($curl, CURLOPT_URL, "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$source_api."&destinations=".$route_destination_pc."&key=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($curl);
            $data = json_decode($output);
            if($data->status == 'OK'){
                $row = $data->rows[0];
                $elements = $row->elements[0];
                $elements_text = $elements->distance->text;
                $driver[$i]['destination_distance_text'] = $elements_text;
                $elements_text = explode(" ",$elements_text);
                $driver[$i]['destination_distance'] = $elements_text[0];

                if((float) $elements_text[0] <= (float) $driver_threshold){
                    $is_driver_exist = 1;
                    $driver[$i]['destination_distance_status'] = 1;
                    

                    $driver[$i]['destination_distance_pobox_status'] = 0;
                    curl_close($curl);
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$route_destination_pc."&destinations=".$route_po_box_pc."&key=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $output_sub = curl_exec($curl);
                    $data_sub = json_decode($output_sub);
                    if($data_sub->status == 'OK'){
                        $row_sub = $data_sub->rows[0];
                        $elements_sub = $row_sub->elements[0];
                        $elements_text_sub = $elements_sub->distance->text;
                        $elements_text_sub = explode(" ",$elements_text_sub);
                        $driver[$i]['route_destination_pc'] = $route_destination_pc; 
                        if((float) $elements_text_sub[0] <= (float) $destination_threshold){
                            $driver[$i]['destination_distance_pobox_status'] = 1;
                            $is_pobox_driver_exist = 1;
                            $driver[$i]['destination_distance_pobox_km'] = $elements_text_sub[0];
                        }else{
                            $driver[$i]['destination_distance_pobox_status'] = 0;
                        }
                    }
                    curl_close($curl);


                }else{
                    $driver[$i]['destination_distance_status'] = 0;
                }

            }
            



        }

    }
    $assign_driver = '';
    if($source_pc != ''){
        foreach($driver as $item){
            if((float) $item['source_distance_status'] > 0  ){
                if((float) $item['source_distance_pobox_status'] > 0 ){
                    if($assign_driver == ''){
                        $assign_driver = $item['driver_id'];
                        $distance = $item['source_distance_pobox_km'];
                        $driver_rate = $item['rate_id'];
                    }else if((float) $item['source_distance'] <   (float) $distance){
                        $assign_driver = $item['driver_id'];
                        $distance = $item['source_distance_pobox_km'];
                        $driver_rate = $item['rate_id'];

                    }
                    

                }
            }

            if((float) $item['destination_distance_status'] > 0  ){
            if((float) $item['destination_distance_pobox_status'] > 0 ){
                    if($assign_driver == ''){
                        $assign_driver = $item['driver_id'];
                        $distance = $item['destination_distance_pobox_status'];
                        $driver_rate = $item['rate_id'];
                    }else if((float) $item['destination_distance'] <   (float) $distance){
                        $assign_driver = $item['driver_id'];
                        $distance = $item['destination_distance_pobox_status'];
                        $driver_rate = $item['rate_id'];

                    }
                }
            }
        }   
    } 
        // print_r('source_pc:'.$source_pc);
        // print_r('iscontinue:'.$iscontinue);
        // print_r('is_driver_exist:'.$is_driver_exist);

        // print_r('is_pobox_driver_exist:'.$is_pobox_driver_exist);
        // print_r('assign_driver:'.$assign_driver);

        if($source_pc != '' && $iscontinue > 0 && $is_driver_exist > 0  && $is_pobox_driver_exist > 0  && $assign_driver != ''){
            $insert_id = $db->insert('collection',$collection_data);
            $order_data['collection_id'] = $insert_id;
            $db->insert('orders',$order_data);
            update_user_subscription($db);
            $db->where('rate_id',$driver_rate);
            $rate_data = $db->getOne('driver_pay_rate');



            $order_transit = array(
                'order_id'=>$insert_id,
                'transit_status'=>'A',
                'driver_id'=>$assign_driver,
                'transit_pay'=>$rate_data['rate'] * $distance,
                'transit_distance'=>$distance
            );
            $db->insert('order_transit',$order_transit);

            update_net_transaction($db,$_SESSION['user']['refId'],$distance);
            $output = array(
                "status" => 1
            );
        }else{
            if($source_pc == ''){
                $errorcode = 201;
            }else if($is_driver_exist < 1){
                $errorcode = 203;
            }else if($is_pobox_driver_exist < 1 ){
                $errorcode = 204;
            }else if($assign_driver == ''){
                $errorcode = 205;
            }
            $output = array(
                "status" => 0
            );

        }
    $output['errorcode'] = $errorcode;
    echo json_encode($output);
});


Flight::route('/insertupdate_warehouse', function () {
    require 'config.php'; 
    $wh_eir = $_POST['wh_eir'];
    $wh_address = $_POST['wh_address'];
    $wh_id = $_POST['wh_id'];
    $wh_pc = '';
    $curl = curl_init();
    $delivery_pc = '';
    curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=".$wh_eir."&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $data = json_decode($output);
    $is_continue = 1;
    $error_code = array();
    if($data->status == 'OK'){
        $plus_code = $data->plus_code;
        $route_source_pc = $plus_code->global_code;
        $wh_pc = $route_source_pc;
    }
    if($wh_pc != ''){
        $data = array(
            'wh_pc'=>$wh_pc,
            'wh_address'=>$wh_address,
            'wh_eir'=>$wh_eir
        );
        if($wh_id !=''){
            $db->where('wh_id',$wh_id);
            $db->update('warehouse',$data);
        }else{
            $db->insert('warehouse',$data);
        }
        $output = array(
            "status" => 1
        );

    }else{
        $output = array(
            "status" => 0
        );

    }
    echo json_encode($output);
});

Flight::route('/driver/manage', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_driver.php";
});

Flight::route('/user/manage', function () {
    require 'config.php'; 
    check_login();
    $subscription = $db->get('subscription_plan');
   require_once "view/home/manage_user.php";
});

Flight::route('/pobox', function () {
    require 'config.php'; 
    check_login();
   require_once "view/home/manage_pobox.php";
});

Flight::route('/profile/edit(/@id)', function ($id) {
    require 'config.php'; 
    check_login();
    $sess_user = $_SESSION['user'];
    $id = $sess_user['refId'];
    $user_type = $sess_user['type'];
    if($user_type == 'driver'){
        $db->where('driver_id', $id);
        $user = $db->getOne('driver');
        require_once "view/home/driver_profile.php";
    }else if($user_type = 'user'){
        $db->where('user_id', $id);
        $user = $db->getOne('user');
        require_once "view/home/user_profile.php";
    }
});

Flight::route('/pobox/create(/@id)', function ($id) {
    require 'config.php'; 
    check_login();
    $pobox = array();
    if($id!=''){
        $db->where('PObox_id', $id);
        $pobox = $db->getOne('pobox');
    }
    require_once "view/home/create_pobox.php";
});

Flight::route('/driver/rate/create(/@id)', function ($id) {
    require 'config.php'; 
    check_login();
    $driver_pay_rate = array();
    if($id!=''){
        $db->where('rate_id', $id);
        $driver_pay_rate = $db->getOne('driver_pay_rate');
    }
    require_once "view/home/create_driver_rate.php";
});

Flight::route('/warehouse/create(/@id)', function ($id) {
    require 'config.php'; 
    check_login();
    $warehouse = array();
    if($id!=''){
        $db->where('wh_id', $id);
        $warehouse = $db->getOne('warehouse');
    }
    require_once "view/home/create_warehouse.php";
});

Flight::route('/pobox_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $sess_user = $_SESSION['user'];
    $user_id = $sess_user['refId'];
    $arraycolums = array('delivery_address', 'delivery_eir');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.PObox_id", "desc");
    }
    if($sess_user['type']!='admin'){
        $db->where('user_id',$user_id);
    }
    $assets = $db->withTotalCount()->get(" pobox as a ", Array($skip, $start), 'a.*,PObox_id as id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['delivery_address'];
        $row[] = $item['delivery_eir'];
        $act = '<a href="' . $base_url . 'pobox/create/' . $item['id'] . '"  tp="confirm" id="' . $item['id'] . '" data-toggle="tooltip" title="Change Status" type="button" class="btn btn-primary pay" data-target="#statusmodal"><i class="ti-pencil"></i></a>';

        $row[] = $act;
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});

Flight::route('/order/updatepayoutstatus', function () {
    require 'config.php'; 
    $driver_id = $_POST['driver_id'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $db->where('driver_id',$driver_id);
    $db->where('year',$year);
    $db->where('month',$month);
    $data = array(
        'payout_status'=>1
    );
    $db->update('driver_payout',$data);
    echo json_encode($data);
});

Flight::route('/user/updatesuscription', function () {
    require 'config.php'; 
    $user_id = $_POST['user_id'];
    $subscription = $_POST['subscription'];
    $db->rawQuery("update user set subscription_status='A',subscription_start_date = curdate(),subscription_end_date = DATE_ADD(curdate(), INTERVAL +30 DAY) ,subscription_plan_id = ".$subscription." where user_id =  ".$user_id);
    $output = array(
        'status'=>1
    );
    echo json_encode($output);
});


Flight::route('/driver/setrate', function () {
    require 'config.php'; 
    $id = $_POST['id'];
    $rate_id = $_POST['rate_id'];
    $data = array(
        'rate_id'=>$rate_id
    );
    $db->where('driver_id',$id);
    $db->update('driver',$data);
    $output = array(
        'status'=>$rate_id
    );
    echo json_encode($output);
});


Flight::route('/order/updatetransiststatus', function () {
    require 'config.php'; 
    $id = $_POST['id'];


    $id = $_POST['id'];
    $data = array(
        'order_status'=>'T'
    );
    $db->where('order_id',$id);
    $db->update('orders',$data);

    $data = array(
        'transit_status'=>'T'
    );

    $db->where('order_id',$id);
    $db->update('order_transit',$data);

    $output = array(
        'status'=>1
    );
    echo json_encode($output);
});




Flight::route('/order/updatedelivered', function () {
    require 'config.php'; 
    $id = $_POST['id'];
    $data = array(
        'transit_status'=>'D'
    );
    $db->where('order_id',$id);
    $db->update('order_transit',$data);



    //$data = array(
      //  'transit_status'=>'C'
    //);

    //$db->where('order_id',$id);
   // $db->update('order_transit',$data);

    $output = array(
        'status'=>1
    );
    echo json_encode($output);
});

Flight::route('/order/updatecollectionstatus', function () {
    require 'config.php'; 
    $id = $_POST['id'];
    $data = array(
        'order_status'=>'C'
    );
    $db->where('order_id',$id);
    $db->update('orders',$data);



    //$data = array(
      //  'transit_status'=>'C'
    //);

    //$db->where('order_id',$id);
   // $db->update('order_transit',$data);

    $output = array(
        'status'=>1
    );
    echo json_encode($output);
});


Flight::route('/driver/activateflag', function () {
    require 'config.php'; 
    $id = $_POST['id'];
    $status = $_POST['status'];
    $data = array(
        'driver_active_flag'=>$status
    );
    $db->where('driver_id',$id);
    $db->update('driver',$data);
    $output = array(
        'status'=>1
    );
    echo json_encode($output);
});

Flight::route('/user/activateflag', function () {
    require 'config.php'; 
    $id = $_POST['id'];
    $status = $_POST['status'];
    $data = array(
        'user_active_flag'=>$status
    );
    $db->where('user_id',$id);
    $db->update('user',$data);
    $output = array(
        'status'=>1
    );
    echo json_encode($output);
});

Flight::route('/driver/approvekyc', function () {
    require 'config.php'; 
    $id = $_POST['id'];
    $status = $_POST['status'];
    $data = array(
        'kyc_status'=>$status
    );
    $db->where('driver_id',$id);
    $db->update('driver_kyc',$data);
    $output = array(
        'status'=>1
    );
    echo json_encode($output);
});

Flight::route('/subscription/create(/@id)', function ($id) {
    require 'config.php'; 
    check_login();
    $subscription = array();
    if($id!=''){
        $db->where('subscription_plan_id', $id);
        $subscription = $db->getOne('subscription_plan');
    }
    require_once "view/home/create_subscription.php";
});

Flight::route('/user_list', function () {
    require 'config.php';
    check_login();
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    if($_POST['subscription_status']){
        $db->where('subscription_status  '.$_POST['subscription_status'].' ');
    }
    if($_POST['user_status']){
        $db->where('user_active_flag  '.$_POST['user_status'].' ');
    }
    $arraycolums = array('user_name');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.user_id", "desc");
    }
    $assets = $db->withTotalCount()->get("   `user` AS  a LEFT JOIN subscription_plan as b on a.subscription_plan_id = b.subscription_plan_id ", Array($skip, $start),"user_id,user_active_flag,a.user_name,a.email,ifnull(b.plan_name,'') as plan_name,IFNULL(b.max_transactions,0) as max_transactions,IFNULL(b.monthly_rate,0) as monthly_rate,subscription_status");
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['user_name'];
        $row[] = $item['email'];
        $row[] = $item['plan_name'];
        if($item['user_active_flag'] == 1){

        }
        if($item['subscription_status'] == 'N'){
            $row[] = 'Inactive ';
        }else if($item['subscription_status'] == 'A'){
            $row[] = ' Active';
        }else if($item['subscription_status'] == 'E'){
            $row[] = ' Expired';
        }else if($item['subscription_status'] == 'T'){
            $row[] = ' Limit Exceeded';
        }else{
            $row[] = 'No Active Subscription';
        }
        if($item['user_active_flag'] == 1){
            $row[] = 'Active';
        }else{
            $row[] = 'Inactive';
        }

        if($item['user_active_flag'] == 1){
            $row[] = '<button   status = 0  attr-id = '.$item['user_id'].' type ="button" class = "btn btn-danger flagactive" >Inactivate</button>';
        }else{
            $row[] = '<button  status = 1 attr-id = '.$item['user_id'].' type ="button" class = "btn btn-info flagactive" >Activate</button>';
        }

        if($item['subscription_status'] != 'A'){
            $row[] = '<button attr-id = '.$item['user_id'].' class = "btn btn-info btnsubscription"  >Add Subscription</button> ';
        }else{
            $row[] = '';
        }
        
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});


Flight::route('/driver_list', function () {
    require 'config.php';
    check_login();
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('driver_name');
    if($_POST['kyc_status']){
        $db->where('kyc_status  '.$_POST['kyc_status'].' ');
    }
    if($_POST['user_status']){
        $db->where('driver_active_flag  '.$_POST['user_status'].' ');
    }

    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.driver_id", "desc");
    }
    $assets = $db->withTotalCount()->get(" `driver` as a INNER join driver_kyc as b on a.driver_id = b.driver_id ", Array($skip, $start), 'rate_id,driver_name,driver_active_flag,service_start_time,service_end_time,b.kyc_status,b.kyc_doc,a.driver_id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    $driver_pay_rate = $db->get('driver_pay_rate');
   

    

    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['driver_name'];
        $txt_rate = '<option value = "0" >Select Rate</option>';
        
        foreach($driver_pay_rate as $rate){
            $sel = '';
            if((float) $rate['rate_id'] == (float) $item['rate_id'] ){
                $sel = 'selected';
            }
            $txt_rate =  $txt_rate . '<option '.$sel.' value = "'.$rate['rate_id'].'" >'.$rate['rate'].'</option>';
        }
        $row[] = '<select attr-id = '.$item['driver_id'].' class = "form-control rate" >'.$txt_rate.'</select ';
        if($item['driver_active_flag'] == 1){
            $row[] = 'Active';
        }else{
            $row[] = 'Inactive';
        }

        if($item['kyc_status'] == 1){
            $row[] = 'Approved';
        }else{
            $row[] = 'Rejected';
        }
        $act = '<div class="btn-group" role="group"><a  href="'.$base_url.'uploads/'.$item['kyc_doc'].'" download="KYC"><i class="ti-download"></i></a>';
        $act = $act .'&nbsp;<a   href="'.$base_url.'uploads/'.$item['kyc_doc'].'" target= "_blank" tp ="new"><i class="ti-eye"></i></a></div>';
        $row[] = $act;
        if($item['driver_active_flag'] == 1){
            $row[] = '<button   status = 0  attr-id = '.$item['driver_id'].' type ="button" class = "btn btn-danger flagactive" >Inactivate</button>';
        }else{
            $row[] = '<button  status = 1 attr-id = '.$item['driver_id'].' type ="button" class = "btn btn-info flagactive" >Activate</button>';
        }

        if($item['kyc_status'] == 1){
            $row[] = '<button status = 0 attr-id = '.$item['driver_id'].' type ="button" class = "btn btn-danger kycactive" >Reject KYC</button>';
        }else{
            $row[] = '<button status = 1  attr-id = '.$item['driver_id'].' type ="button" class = "btn btn-warning kycactive" >Approve KYC</button>';
        }
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});

Flight::route('/subscription_list', function () {
    require 'config.php';
    $start = $_REQUEST['length'];
    $skip = $_REQUEST['start'];
    $search = $_REQUEST['search'];
    $arraycolums = array('plan_name', 'monthly_rate', 'plan_feature');
    if ($search['value'] != '') {
        $db->where(implode(" like ('%" . $search['value'] . "%') or  ", $arraycolums), '%' . $search['value'] . '%', 'like');
    }
    $sort = $_REQUEST['order'];
    if ($sort[0]['column'] < sizeof($arraycolums)) {
        $db->orderBy($arraycolums[$sort[0]['column']], $sort[0]['dir']);
    } else {
        $db->orderBy("a.subscription_plan_id", "desc");
    }
    $assets = $db->withTotalCount()->get(" subscription_plan as a ", Array($skip, $start), 'a.*,subscription_plan_id as id');
    $total = $db->totalCount;
    $data = array();
    $cnt = $skip;
    foreach ($assets as $item) {
        $row = array();
        $cnt++;
        $row[] = $cnt;
        $row[] = $item['plan_name'];
        $row[] = $item['max_transactions'];
        $row[] = $item['monthly_rate'];
        $act = '<a href="' . $base_url . 'subscription/create/' . $item['id'] . '"  tp="confirm" id="' . $item['id'] . '" data-toggle="tooltip" title="Change Status" type="button" class="btn btn-primary pay" data-target="#statusmodal"><i class="ti-pencil"></i></a>';

        $row[] = $act;
        $data[] = $row;
    }
    $output = array(
        "draw" => $_REQUEST['draw'],
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data,
    );

    echo json_encode($output);

});




Flight::route('/insertupdate_subscription', function () {
    require 'config.php'; 
    $subscription_plan_id = $_POST['subscription_plan_id'];
    $plan_name = $_POST['plan_name'];
    $max_transactions = $_POST['max_transactions'];
    $monthly_rate = $_POST['monthly_rate'];
    $plan_feature = $_POST['plan_feature'];
    $max_weight = $_POST['max_weight'];
    $data = array(
        'plan_feature'=>$plan_feature,
        'plan_name'=>$plan_name,
        'max_transactions'=>$max_transactions,
        'monthly_rate'=>$monthly_rate,
        'max_weight'=>$max_weight
    );
    if($subscription_plan_id == ''){
        $db->insert('subscription_plan',$data);
    }else{
        $db->where('subscription_plan_id',$subscription_plan_id);
        $db->update('subscription_plan',$data);
    }

    $output = array(
        "status" => 1
    );
    //output to json format
    echo json_encode($output);
    
});

Flight::route('/insertupdate_driver_rate', function () {
    require 'config.php'; 
    $rate = $_POST['rate'];
    $rate_id = $_POST['rate_id'];
    $data = array(
        'rate'=>$rate
    );

    if($rate_id !=''){
        $db->where('rate_id',$rate_id);
        $db->update('driver_pay_rate',$data);
    }else{
        $db->insert('driver_pay_rate',$data);
    }
    $output = array(
        "status" => 1
    );
   
    echo json_encode($output);
});

Flight::route('/insertupdate_pobox', function () {
    require 'config.php'; 
    $delivery_eir = $_POST['delivery_eir'];
    $delivery_address = $_POST['delivery_address'];
    $PObox_id = $_POST['PObox_id'];
    $sess_user = $_SESSION['user'];
    $user_id = $sess_user['refId'];
    $curl = curl_init();
    $delivery_pc = '';
    curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=".$delivery_eir."&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $data = json_decode($output);
    $is_continue = 1;
    $error_code = array();
    if($data->status == 'OK'){
        $plus_code = $data->plus_code;
        $route_source_pc = $plus_code->global_code;
        $delivery_pc = $route_source_pc;
    }
    if($delivery_pc != ''){
        $data = array(
            'delivery_pc'=>$delivery_pc,
            'delivery_address'=>$delivery_address,
            'delivery_eir'=>$delivery_eir,
        );
        if($PObox_id !=''){
            $db->where('PObox_id',$PObox_id);
            $db->update('pobox',$data);
        }else{
            $data['user_id'] = $user_id;
            $db->insert('pobox',$data);
        }
        $output = array(
            "status" => 1
        );

    }else{
        $output = array(
            "status" => 0
        );

    }
    echo json_encode($output);
});

Flight::route('/insertupdateuser', function () {
    require 'config.php'; 
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_eir = $_POST['eircode'];
    $password = $_POST['password'];
    $db->where('username', $email);
    $is_exist = $db->getOne('users');
    if(!isset($is_exist)){
        $data = array(
            'username'=>$name,
            'user_name'=>$name,
            'password'=>$password,
            'email'=>$email,
            'user_eir'=>$user_eir,
        );
        $insert_id = $db->insert('user', $data);
        $data_user = array(
            'refId'=>$insert_id,
            'username'=>$_POST['email'],
            'password'=>$_POST['password'],
            'type'=>'user'
        );
        $db->insert('users', $data_user);
        $output = array(
            "status" => 1
        );
    }else{
        $output = array(
            "status" => 0
        );
    }

    echo json_encode($output);

});

Flight::route('/update_user_profile', function () {
    require 'config.php'; 
    $user_id = $_POST['user_id'];
    $user_eir = $_POST['user_eir'];
    $curl = curl_init();
    $user_PC ='';
    curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=".$user_eir."&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $data = json_decode($output);
    $is_continue = 1;
    $error_code = array();
    if($data->status == 'OK'){
        $plus_code = $data->plus_code;
        $user_PC = $plus_code->global_code;
        
    }
    curl_close($curl);
   
   
    
        $ins_array = array(
            'username'=>$_POST['email'],
            'email'=>$_POST['email'],
            'user_name'=>$_POST['user_name'],
            'phone'=>$_POST['phone'],
            'user_address'=>$_POST['user_address'],
            'password'=>$_POST['password'],
            'user_eir'=>$user_eir,
            'user_PC'=>$user_PC,
            
        );
        $insert_id = $user_id ;
        $db->where('user_id',$insert_id);
        $db->update('user', $ins_array);
        

    

    $output = array(
        "status" => $is_continue,
        "errorcodes" => $error_code
    );
    //output to json format
    echo json_encode($output);
    
});


Flight::route('/update_driver_profile', function () {
    require 'config.php'; 
    $driver_id = $_POST['driver_id'];
    $source_eircode = $_POST['route_source_eir'];
    $destination_eircode = $_POST['route_destination_eir'];
    $curl = curl_init();
    $route_source_pc ='';
    $route_destination_pc = '';
    curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=".$source_eircode."&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $data = json_decode($output);
    $is_continue = 1;
    $error_code = array();
    if($data->status == 'OK'){
        $plus_code = $data->plus_code;
        $route_source_pc = $plus_code->global_code;
        
    }else{
        $source_error_code = 1;
        $is_continue = 0;
        $error_code[] = 201;
    }
    curl_close($curl);
    if($route_source_pc == ''){
        $source_error_code = 1;
        $is_continue = 0;
        $error_code[] = 201;
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=".$destination_eircode."&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $data = json_decode($output);
    if($data->status == 'OK'){
        $plus_code = $data->plus_code;
        $route_destination_pc = $plus_code->global_code;
    }else{
        $source_error_code = 1;
        $is_continue = 0;
        $error_code[] = 202;
    }
    curl_close($curl);
    if($route_destination_pc ==''){
        $source_error_code = 1;
        $is_continue = 0;
        $error_code[] = 202;
    }
    if($is_continue > 0 ){
        $kyc_doc = '';
        if (isset($_FILES['kyc'])) {  
            if (isset($_FILES['kyc']['name'])) {    
                if($_FILES['kyc']['name'] !=''){ 
                    $file = $_FILES['kyc'];
                    $exp = explode(".", $_FILES['kyc']['name']);
                    $type = 'image';
                    $fileid = 'kyc';
                    $img_a = time() . rand() .'.' . $exp[1];
                    $uploadfile =   'uploads/'.$img_a;
                    if (move_uploaded_file($_FILES['kyc']['tmp_name'], $uploadfile)) {
                        $kyc_doc = $img_a;
                    }else{
                        $error_code[] = 203;
                        $is_continue = 0 ;
                    }
                }
            }
        }
    }
    if($is_continue > 0 ){
        $ins_array = array(
            'username'=>$_POST['driver_email'],
            'driver_email'=>$_POST['driver_email'],
            'driver_name'=>$_POST['driver_name'],
            'phone'=>$_POST['phone'],
            'driver_address'=>$_POST['driver_address'],
            'route_source_eir'=>$source_eircode,
            'route_destination_eir'=>$destination_eircode,
            'route_source_pc'=>$route_source_pc,
            'route_destination_pc'=>$route_destination_pc,
            'service_start_time'=>$_POST['service_start_time'],
            'service_end_time'=>$_POST['service_end_time'],
        );
        $insert_id = $driver_id ;
        $db->where('driver_id',$insert_id);
        $db->update('driver', $ins_array);
        if($kyc_doc != ''){
            $this->db->where('driver_id', $insert_id);
            $this->db->delete('driver_kyc');
            $data_kyc= array(
                'kyc_status'=>0,
                'kyc_doc'=>$kyc_doc,
                'driver_id'=>$insert_id
            );
            $db->insert('driver_kyc', $data_kyc);
        }
    }

    

    $output = array(
        "status" => $is_continue,
        "errorcodes" => $error_code
    );
    //output to json format
    echo json_encode($output);
    
});

Flight::route('/insertupdatedriver', function () {
    require 'config.php'; 
    $email = $_POST['email'];
    $db->where('username', $email);
    $is_exist = $db->getOne('users');
    if(!isset($is_exist)){

    
        $source_eircode = $_POST['source_eircode'];
        $destination_eircode = $_POST['destination_eircode'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=".$source_eircode."&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        $data = json_decode($output);
        $is_continue = 1;
        $error_code = array();
        if($data->status == 'OK'){
            $plus_code = $data->plus_code;
            $route_source_pc = $plus_code->global_code;
            
        }else{
            $source_error_code = 1;
            $is_continue = 0;
            $error_code[] = 201;
        }
        curl_close($curl);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=".$destination_eircode."&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        $data = json_decode($output);
        $is_continue = 1;
        if($data->status == 'OK'){
            $plus_code = $data->plus_code;
            $route_destination_pc = $plus_code->global_code;
        }else{
            $source_error_code = 1;
            $is_continue = 0;
            $error_code[] = 202;
        }
        curl_close($curl);

        if($is_continue > 0 ){
            $kyc_doc = '';
            if (isset($_FILES['kyc'])) {    
                $file = $_FILES['kyc'];
                $exp = explode(".", $_FILES['kyc']['name']);
                $type = 'image';
                $fileid = 'kyc';
                $img_a = time() . rand() .'.' . $exp[1];
                $uploadfile =   'uploads/'.$img_a;
                if (move_uploaded_file($_FILES['kyc']['tmp_name'], $uploadfile)) {
                    $kyc_doc = $img_a;
                }else{
                    $error_code[] = 203;
                    $is_continue = 0 ;
                }
            }
        }
        if($is_continue > 0 ){
            $ins_array = array(
                'username'=>$_POST['email'],
                'driver_email'=>$_POST['email'],
                'driver_name'=>$_POST['name'],
                'route_source_eir'=>$source_eircode,
                'route_destination_eir'=>$destination_eircode,
                'route_source_pc'=>$route_source_pc,
                'route_destination_pc'=>$route_destination_pc,
                'service_start_time'=>$_POST['start_time'],
                'service_end_time'=>$_POST['end_time'],
                'password'=>$_POST['password']
            );
            $insert_id = $db->insert('driver', $ins_array);

            $data_user = array(
                'refId'=>$insert_id,
                'username'=>$_POST['email'],
                'password'=>$_POST['password'],
                'type'=>'driver'
            );
            $db->insert('users', $data_user);
            $data_kyc= array(
                'kyc_status'=>0,
                'kyc_doc'=>$kyc_doc,
                'driver_id'=>$insert_id
            );
            $db->insert('driver_kyc', $data_kyc);
        }

    }else{
        $error_code = array();
        $error_code[] = 204;
        $is_continue = 0;
    }

    $output = array(
        "status" => $is_continue,
        "errorcodes" => $error_code
    );
    //output to json format
    echo json_encode($output);
    
});


Flight::route('/userregistration', function () {
    require 'config.php'; 
   require_once "view/home/userregistration.php";
});

Flight::route('/driverregistration', function () {
    require 'config.php'; 
   require_once "view/home/driverregistration.php";
});



Flight::route('/dashboard1', function () {
    require 'config.php'; 
    check_login();

    // create & initialize a curl session
$curl = curl_init();

// set our url with curl_setopt()

//curl_setopt($curl, CURLOPT_URL, "https://plus.codes/api?address=H91E099&ekey=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw&email=YOUR_EMAIL_HERE");
curl_setopt($curl, CURLOPT_URL, "https://maps.googleapis.com/maps/api/distancematrix/json?origins=9C4HFMCV%2BFJ&destinations=9C5G7WH7%2BCP&key=AIzaSyB6CBOjHsJcWYR9xwkwFC1q2NcfK0dbSEw");
// return the transfer as a string, also with setopt()
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// curl_exec() executes the started curl session
// $output contains the output string
$output = curl_exec($curl);
$data = json_decode($output);


// For Convert Api
        //if($data->status == 'OK'){
        //  $plus_code = $data->plus_code;
            //print_r($plus_code->global_code);
        //}else{
        //
        //}
// For Distance Api
        // if($data->status == 'OK'){
        //     $row = $data->rows[0];
        //     $elements = $row->elements[0];
        //     print_r($elements->distance->text);

        // }
// close curl resource to free up system resources
// (deletes the variable made by curl_init)
curl_close($curl);
   // require_once "view/home/dashboard.php";
});


