<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Struk</title>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
        }

        .text-primary {
            color: gray;
        }
    </style>
</head>

<body>
    <div style="width: 50%; transform: translateX(50%);">
        <h3 style="text-align: center;">Struk Pembayaran</h3>
        <hr class="text-primary">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td class="text-primary">Waktu</td>
                    <td align="right" class="text-primary">
                        {{ \Carbon\Carbon::make($sale->tanggal)->isoFormat('DD MMMM Y') }}</td>
                </tr>
                <tr>
                    <td class="text-primary">No. Struk</td>
                    <td align="right" class="text-primary">{{ $sale->no_faktur }}</td>
                </tr>
                <tr>
                    <td class="text-primary">Nama Pelanggan</td>
                    <td align="right" class="text-primary">{{ $sale->customer->nama }}</td>
                </tr>
            </tbody>
        </table>
        <hr class="text-primary">
        <h3 style="text-align: center;">DETAIL</h3>
        <hr class="text-primary">
        <table style="width: 100%;">
            <tbody>
                @foreach ($sale->saleDetails as $sale_detail)
                    <tr>
                        <td>{{ $sale_detail->product->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-primary">{{ numberFormat($sale_detail->product->harga, 'Rp.') }} x
                            {{ $sale_detail->qty }}</td>
                        <td align="right" class="text-primary">{{ numberFormat($sale_detail->total, 'Rp.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr class="text-primary">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td>Total</td>
                    <td align="right">{{ numberFormat($sale->jumlah, 'Rp.') }}</td>
                </tr>
            </tbody>
        </table>
        <hr class="text-primary">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td class="text-primary">Bayar</td>
                    <td align="right" class="text-primary">{{ numberFormat($sale->total_bayar, 'Rp.') }}</td>
                </tr>
                <tr>
                    <td class="text-primary">Kembalian</td>
                    <td align="right" class="text-primary">{{ numberFormat($sale->kembalian, 'Rp.') }}</td>
                </tr>
            </tbody>
        </table>
        <hr class="text-primary">
    </div>
</body>

</html>
