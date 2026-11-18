<?php
// ===== VARIABLES =====
$storeName = 'CORTIS ALBUM';
$customer = 'Patricia';
$membership = 'gold';        // try 'silver' or 'regular'
$taxRate = 0.12;             // 12% tax
$shippingFee = 5;
$freeShippingMinimum = 150;
$photocardEvery = 50;        // 1 free photocard for every $50 spent

// ===== ARRAYS =====
// Index 0 = 1st EP, 1 = 2nd EP, 2 = 3rd EP
$titles = [
    '1ST EP: COLOR OUTSIDE THE LINES (2025)',
    '2ND EP: GREENGREEN (2026)',
    '3RD EP: GREENGREEN_PLAYEREXTENDED (2026)'
];
$prices = [32.34, 68.66, 88.75];
$quantities = [2, 1, 1];     // what the customer wants to buy
$stock = [10, 3, 1];         // copies left in the store

// ===== FOR LOOP: compute every line total, subtotal and item count =====
$lineTotals = [];
$subtotal = 0;
$itemsPurchased = 0;

for ($i = 0; $i < count($titles); $i++) {
    // IF: nobody can buy more copies than the store has
    if ($quantities[$i] > $stock[$i]) {
        $quantities[$i] = $stock[$i];
    }
    $lineTotals[$i] = $prices[$i] * $quantities[$i];
    $subtotal += $lineTotals[$i];
    $itemsPurchased += $quantities[$i];
}

// ===== SWITCH: membership level =====
switch ($membership) {
    case 'gold':
        $memberRate = 0.05;
        $memberLabel = 'Gold member';
        break;
    case 'silver':
        $memberRate = 0.02;
        $memberLabel = 'Silver member';
        break;
    default:
        $memberRate = 0;
        $memberLabel = 'Regular';
}

// ===== IF / ELSEIF / ELSE: bundle discount tiers (percent kept as a string) =====
if ($subtotal >= 300) {
    $discountPercent = '15';
} elseif ($subtotal >= 150) {
    $discountPercent = '10';
} elseif ($subtotal >= 100) {
    $discountPercent = '5';
} else {
    $discountPercent = '0';
}

// ===== LOGICAL OPERATOR =====
$qualifies = $subtotal >= 100 && $itemsPurchased >= 3;

// ===== TYPE JUGGLING =====
$discountRate = $discountPercent / 100;                   // string becomes a number
$bundleDiscount = $subtotal * $discountRate * $qualifies; // true = 1, false = 0
$memberDiscount = $subtotal * $memberRate;
$discount = $bundleDiscount + $memberDiscount;
$afterDiscount = $subtotal - $discount;

// ===== IF / ELSE: shipping =====
if ($afterDiscount >= $freeShippingMinimum) {
    $shipping = 0;
} else {
    $shipping = $shippingFee;
}

// ===== EXPRESSIONS =====
$tax = $afterDiscount * $taxRate;
$total = $afterDiscount + $shipping + $tax;

// ===== WHILE LOOP: 1 free photocard for every $50 spent =====
$photocards = 0;
$remaining = $afterDiscount;
while ($remaining >= $photocardEvery) {
    $photocards++;
    $remaining -= $photocardEvery;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title><?php echo $storeName; ?></title>
</head>
<body>
    <h1><?php echo $storeName; ?></h1>
    <h2>Welcome COER <?php echo $customer; ?> <span class="badge"><?php echo $memberLabel; ?></span></h2>
    <p>As you proceed for the payment, below is the result of your purchase:</p>

    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($titles as $i => $title) {
                // IF / ELSEIF / ELSE: stock label for each album
                if ($stock[$i] == 1) {
                    $status = 'Last copy';
                    $statusClass = 'low';
                } elseif ($stock[$i] <= 3) {
                    $status = 'Low stock';
                    $statusClass = 'low';
                } else {
                    $status = 'In stock';
                    $statusClass = 'ok';
                }
            ?>
            <tr>
                <td><?php echo $title; ?></td>
                <td>$<?php echo number_format($prices[$i], 2); ?></td>
                <td>
                    <?php echo $quantities[$i]; ?>
                    <span class="pips"><?php
                        // NESTED FOR LOOP: one square per copy
                        for ($q = 1; $q <= $quantities[$i]; $q++) {
                            echo '■';
                        }
                    ?></span>
                </td>
                <td>$<?php echo number_format($lineTotals[$i], 2); ?></td>
                <td class="<?php echo $statusClass; ?>"><?php echo $status; ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Items purchased</td>
                <td><?php echo $itemsPurchased; ?></td>
            </tr>
            <tr>
                <td colspan="4">Subtotal</td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <?php if ($bundleDiscount > 0) { ?>
            <tr>
                <td colspan="4">Bundle discount (<?php echo $discountPercent; ?>%)</td>
                <td>-$<?php echo number_format($bundleDiscount, 2); ?></td>
            </tr>
            <?php } ?>
            <?php if ($memberDiscount > 0) { ?>
            <tr>
                <td colspan="4"><?php echo $memberLabel; ?> discount (<?php echo $memberRate * 100; ?>%)</td>
                <td>-$<?php echo number_format($memberDiscount, 2); ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="4">Shipping</td>
                <td><?php if ($shipping == 0) { echo 'FREE'; } else { echo '$' . number_format($shipping, 2); } ?></td>
            </tr>
            <tr>
                <td colspan="4">Tax (<?php echo $taxRate * 100; ?>%)</td>
                <td>$<?php echo number_format($tax, 2); ?></td>
            </tr>
            <tr class="total">
                <td colspan="4">Total</td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
        </tfoot>
    </table>
    </div>

    <p class="note">
        <?php if ($qualifies) {
            echo 'Bundle discount unlocked! ';
        } else {
            echo 'Buy 3 or more items worth at least $100 to unlock the bundle discount. ';
        } ?>
        You earned <?php echo $photocards; ?> free photocard(s).
    </p>

    <p class="note">&copy; <?php echo $storeName; ?> - thank you for shopping, <?php echo $customer; ?>!</p>
</body>
</html>
