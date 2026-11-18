<?php
// ===== VARIABLES =====
$storeName = 'CORTIS ALBUM';
$customer = 'Patricia';
$discountPercent = '10';   // 10% bundle discount (stored as a string on purpose)
$discountMinimum = 150;    // minimum subtotal to qualify
$taxRate = 0.12;           // 12% tax

// ===== ARRAYS =====
$titles = [
    '1ST EP: COLOR OUTSIDE THE LINES (2025)',
    '2ND EP: GREENGREEN (2026)',
    '3RD EP: GREENGREEN_PLAYEREXTENDED (2026)'
];
$prices = [32.34, 68.66, 88.75];
$quantities = [1, 1, 1];

// ===== ARITHMETIC OPERATORS (price x quantity) =====
$line0 = $prices[0] * $quantities[0];
$line1 = $prices[1] * $quantities[1];
$line2 = $prices[2] * $quantities[2];

// ===== ASSIGNMENT OPERATORS =====
$subtotal = 0;
$subtotal += $line0;
$subtotal += $line1;
$subtotal += $line2;

$itemsPurchased = 0;
$itemsPurchased += $quantities[0];
$itemsPurchased += $quantities[1];
$itemsPurchased += $quantities[2];

// ===== COMPARISON + LOGICAL OPERATORS =====
// Discount only if the subtotal is high enough AND at least 3 items were bought
$qualifies = $subtotal >= $discountMinimum && $itemsPurchased >= 3;

// ===== TYPE JUGGLING =====
// PHP converts a value's type automatically depending on how it is used.
$discountRate = $discountPercent / 100;               // string '10' becomes a number: 0.1
$discount = $subtotal * $discountRate * $qualifies;   // boolean becomes a number: true = 1, false = 0
$message = 'Discount applied (1 = yes, 0 = no): ' . (int)$qualifies; // (int) is an explicit cast

// ===== EXPRESSIONS =====
$afterDiscount = $subtotal - $discount;
$tax = $afterDiscount * $taxRate;
$total = $afterDiscount + $tax;
$average = $subtotal / $itemsPurchased;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?php echo $storeName; ?></title>
</head>
<body>
    <h1><?php echo $storeName; ?></h1>
    <h2>Welcome COER <?php echo $customer; ?></h2>
    <p>As you proceed for the payment, below is the result of your purchase:</p>

    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $titles[0]; ?></td>
                <td>$<?php echo number_format($prices[0], 2); ?></td>
                <td><?php echo $quantities[0]; ?></td>
                <td>$<?php echo number_format($line0, 2); ?></td>
            </tr>
            <tr>
                <td><?php echo $titles[1]; ?></td>
                <td>$<?php echo number_format($prices[1], 2); ?></td>
                <td><?php echo $quantities[1]; ?></td>
                <td>$<?php echo number_format($line1, 2); ?></td>
            </tr>
            <tr>
                <td><?php echo $titles[2]; ?></td>
                <td>$<?php echo number_format($prices[2], 2); ?></td>
                <td><?php echo $quantities[2]; ?></td>
                <td>$<?php echo number_format($line2, 2); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Items purchased</td>
                <td><?php echo $itemsPurchased; ?></td>
            </tr>
            <tr>
                <td colspan="3">Subtotal</td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <tr>
                <td colspan="3">Discount (<?php echo $discountRate * 100; ?>%)</td>
                <td>-$<?php echo number_format($discount, 2); ?></td>
            </tr>
            <tr>
                <td colspan="3">Tax (<?php echo $taxRate * 100; ?>%)</td>
                <td>$<?php echo number_format($tax, 2); ?></td>
            </tr>
            <tr class="total">
                <td colspan="3">Total</td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
        </tfoot>
    </table>
    </div>

    <p class="note"><?php echo $message; ?> Average price per EP: $<?php echo number_format($average, 2); ?></p>
</body>
</html>