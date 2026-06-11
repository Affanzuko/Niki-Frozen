@extends('layouts.app')

@section('title', 'Struk Pembelian - Niki Frozen')

@push('styles')
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0 auto;
            padding: 10px;
            font-size: 11px;
            color: #000;
            max-width: 80mm; /* Simulasi kertas thermal 80mm */
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .info {
            margin-bottom: 10px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        th, td {
            text-align: left;
            vertical-align: top;
            padding: 2px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .item-row td {
            padding-bottom: 5px;
        }
        .totals {
            margin-top: 10px;
        }
        .totals table {
            width: 100%;
        }
        .totals td {
            padding: 2px 0;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
    </style>
@endpush

@section('body_class', 'bg-white text-black')

@section('content')
    <div class="header">
        <h1>Niki Frozen</h1>
        <p>Jl. Contoh No. 123, Kota</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <table style="width:100%">
            <tr>
                <td>Tanggal</td>
                <td class="text-right">{{ date('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Metode</td>
                <td class="text-right">{{ strtoupper($data['method']) }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <table>
        @foreach($items as $item)
        <tr class="item-row">
            <td colspan="3">
                {{ $item['nama'] }}<br>
                {{ $item['jumlah'] }} x {{ number_format($item['harga'], 0, ',', '.') }}
            </td>
            <td class="text-right" style="vertical-align: bottom;">
                {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ number_format($data['subtotal'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak (11%)</td>
                <td class="text-right">{{ number_format($data['tax'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold; font-size:12px;">Total</td>
                <td class="text-right" style="font-weight:bold; font-size:12px;">
                    {{ number_format($data['total'], 0, ',', '.') }}
                </td>
            </tr>
            @if(strtolower($data['method']) === 'tunai' || strtolower($data['method']) === 'cash')
            <tr>
                <td>Tunai</td>
                <td class="text-right">{{ number_format($data['received'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right">{{ number_format($data['kembalian'], 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

@endsection

@push('scripts')
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
@endpush
