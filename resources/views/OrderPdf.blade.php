<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <h1>Laporan Order</h1>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Customer</th>
                <th scope="col">Total</th>
                <th scope="col">Metode</th>
                <th scope="col">Status Pembayaran</th>
                <th scope="col">Pengiriman</th>
                <th scope="col">Status Order</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @foreach ($order as $orders)
            <tr>
                <th scope="row">{{$orders->address->full_name}}</th>
                <td>{{$orders->grand_total}}</td>
                <td>{{$orders->payment_method}}</td>
                <td>{{$orders->payment_status}}</td>
                <td>{{$orders->shipping_method}}</td>
                <td>{{$orders->status}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
