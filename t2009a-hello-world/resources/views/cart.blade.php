<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body>

<div class="w3-container">
    <h2>Basic Table</h2>

    <table class="w3-table">
        <tr>
            <th>Product Id</th>
            <th>Product Name</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Total price</th>
            <th>Action</th>
        </tr>
        <?php
        $totalPrice = 0;
        ?>
        @foreach($shoppingCart as $cartItem)
            <?php
            if (isset($cartItem)) {
                $totalPrice += $cartItem->unitPrice * $cartItem->quantity;
            }
            ?>
            <tr>
                <form action="/cart/update" method="post">
                    @csrf
                    <td>{{$cartItem->id}}</td>
                    <td>{{$cartItem->name}}</td>
                    <td>{{$cartItem->unitPrice}}</td>
                    <td>
                        <input type="hidden" name="id" value="{{$cartItem->id}}">
                        <input name="quantity" class="w3-input w3-border w3-quarter" min="1" type="number" value="{{$cartItem->quantity}}">
                    </td>
                    <td>{{$cartItem->unitPrice * $cartItem->quantity}}</td>
                    <td>
                        <button class="w3-button w3-indigo">Update</button>
                        <a href="/cart/delete?id={{$cartItem->id}}" onclick="return confirm ('Are you sure to delete this products?')" class="w3-button w3-red">Delete</a>
                    </td>
                </form>
            </tr>
        @endforeach
    </table>
    <div style="margin-top: 20px">
        <strong>Total price: {{$totalPrice}}</strong>
    </div>
</div>

</body>
</html>
