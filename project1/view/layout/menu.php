<ul>
    <li class="label">Main</li>
    <li class="active"><a class="sidebar-sub-toggle"><i class="ti-home"></i> Welcome <span class="badge badge-primary"></span> <span class="sidebar-collapse-icon ti-angle-down"></span></a>
        <ul>
            <?php $sess_user = $_SESSION['user']; ?>
            <?php if($sess_user['type'] == 'driver' || $sess_user['type'] == 'user' ){?>
                <li><a href="<?php echo $base_url;?>profile">Profile</a></li>

            <?php }?>
            <?php if( $sess_user['type'] == 'user' ){?>
                <li><a href="<?php echo $base_url;?>clickandcollect">Click & Collect</a></li>
                <li><a href="<?php echo $base_url;?>orders/manage">Orders</a></li>
                <li><a href="<?php echo $base_url;?>user/net_transaction">Sustainability Profile</a></li>
                <?php }?>
            <?php if($sess_user['type'] == 'driver'  ){?>
                <li><a href="<?php echo $base_url;?>driver/transit_history">Transit History</a></li>
                <li><a href="<?php echo $base_url;?>driver/payout/pending">Pending Payout</a></li>
                <li><a href="<?php echo $base_url;?>driver/payout/paid">Payout</a></li>
            <?php }?>
            <?php if($sess_user['type'] == 'admin'){?>
                <li><a href="<?php echo $base_url;?>payout">Manage Payout</a></li>
                <li><a href="<?php echo $base_url;?>subscription">Manage Subscription</a></li>
                <li><a href="<?php echo $base_url;?>subscription/create">Create Subscription</a></li>
            <?php }?>
            <?php if($sess_user['type'] == 'admin' || $sess_user['type'] == 'user' ){?>
                <li><a href="<?php echo $base_url;?>pobox">Manage PObox</a></li>
                <li><a href="<?php echo $base_url;?>pobox/create">Create PObox</a></li>
            <?php }?>
            <?php if($sess_user['type'] == 'admin'){?>
                <li><a href="<?php echo $base_url;?>driver/manage">Manage Driver</a></li>
                <li><a href="<?php echo $base_url;?>user/manage">Manage User</a></li>
                <li><a href="<?php echo $base_url;?>warehouse">Manage WareHouse</a></li>
                <li><a href="<?php echo $base_url;?>warehouse/create">Create WareHouse</a></li>
                <li><a href="<?php echo $base_url;?>driver/rate">Manage Driver Rates</a></li>
                <li><a href="<?php echo $base_url;?>driver/rate/create">Create Driver Rate</a></li>        
            <?php }?>

        </ul>
    </li>
    
</ul>