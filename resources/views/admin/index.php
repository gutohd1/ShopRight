<?php
echo '<h1>Admin area</h1>';
if (isset($notifications) && ! empty($notifications)) {
    ?>
    <div>
        <h2>Notifications</h2>
        <table>
            <thead>
                <td>
                    Product ID
                </td>
                <td>
                    Message
                </td>
            </thead>
            <tbody>
            <?php
            foreach ($notifications as $productId => $notification) {
                ?>
                <tr>
                    <td><?php echo $productId; ?></td>
                    <td><?php echo $notification; ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
}
if (isset($orders) && ! empty($orders)) {
    ?>
    <div>
        <h2>Orders</h2>
        <table>
            <thead>
            <td>
                Order ID
            </td>
            <td>
                Products
            </td>
            <td>
                Total
            </td>
            </thead>
            <tbody>
            <?php
            foreach ($orders as $id => $order) {
                ?>
                <tr>
                    <td><?php echo $id; ?></td>
                    <td><?php
                        foreach ($order->products as $product) {
                            echo '<p>'.$product->quantity.'X Product ID: '.$product->product_id.' £:'.$product->price.'</p>';
                        }
                    ?></td>
                    <td><?php echo $order->total; ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
}

