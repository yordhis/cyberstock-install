<!DOCTYPE html>
<html>

<head>
    {{-- <link rel="stylesheet" href="style.css"> <script src="script.js"></script> --}}
    <style>
        * {
            margin-top: 0%;
            font-size: 20px;
            font-family: 'Times New Roman';
        }



        td,
        th,
        tr,
        table {
            border-top: 1px solid rgb(27, 25, 25);
            border-collapse: collapse;
        }

        td.producto,
        th.producto {
            width: 200px;
            max-width: 200px;
            text-align: left;
        }

        td.cantidad,
        th.cantidad {
            width: 0px;
            max-width: 0px;
            word-break: break-all;
        }

        td.precio,
        th.precio {
            font-size: 18px;
            width: 160px;
            max-width: 160px;
            word-break: break-all;
            text-align: right;
        }

        .centrado {
            margin: 0%;
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 325px;
            max-width: 355px;
        }

        img {
            max-width: 150px;
            width: 150px;
            margin-top: 3%;
            margin-bottom: 0%;
            padding-left: 28%;
        }
    </style>
</head>

<body>
    @if (count($pos))
        <div class="ticket" id="ticket">
            @if ($pos[0]->estatusImagen)
                <img src="{{ $pos[0]->imagen }}" alt="Logotipo">
            @endif

            <p class="centrado">
                {{-- {{ $factura->iva > 0 ? 'SENIAT' : '' }} --}}
                <br>{{ $pos[0]->rif }}
                <br>{{ $pos[0]->direccion }}
                <br>ZONA POSTAL {{ $pos[0]->postal }}
            </p>
            <table>
                <thead>
                    <tr>
                        <th class="producto">FACTURA</th>

                        <th class="precio">{{ $factura->codigo }}</th>
                    </tr>
                    <tr>
                        <th class="producto">FECHA</th>

                        <th class="precio">{{ date_format(date_create(explode(' ', $factura->created_at)[0]), 'd-m-Y') }}
                        </th>
                    </tr>
                    <tr>
                        <th class="producto">HORA</th>

                        <th class="precio">{{ date_format(date_create(explode(' ', $factura->created_at)[1]), 'h:i:s') }}
                        </th>
                    </tr>
                    {{-- <tr>
                        <th class="producto">CANT. PRODUCTO</th>

                        <th class="precio">$$</th>
                    </tr> --}}
                </thead>
                <tbody>
                    @foreach ($factura->carrito as $producto)
                        <tr>
                            <td class="producto">{{ $producto->cantidad }} X {{ $producto->descripcion }}</td>

                            <td class="precio">Bs
                                {{ number_format($producto->subtotal * $factura->tasa, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach


                    <tr>
                        <td class="producto">
                            |Total de Articulos:{{ $factura->total_articulos }}| <br>
                            SUB-TOTAL <br>
                        </td>

                        <td class="precio"><br> Bs
                            {{ number_format($factura->subtotal * $factura->tasa, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="producto">IVA </td>

                        <td class="precio">Bs
                            {{ number_format($factura->subtotal * $factura->tasa * $utilidades[0]->iva['restar'], 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="producto">TOTAL</td>

                        <td class="precio">Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="producto">EFECTIVO 3</td>

                        <td class="precio">Bs {{ number_format($factura->total * $factura->tasa, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>


            <p class="centrado">
                ¡GRACIAS POR SU COMPRA!
            </p>
        </div>
    @else
        <h1 style="color: red;">Debe configurar los datos del POS</h1>
        <p>Para proceder a la impresión de factura</p>
    @endif

</body>

</html>
