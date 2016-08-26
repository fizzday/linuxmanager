@extends('layouts.app')

@section('content')
    <style>
        html, body {
            height: 90%;
        }

        .index-page {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: table;
            font-weight: 100;
        }

        .index-page .row {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .index-page .content {
            text-align: center;
            display: inline-block;
            /*background-color: #00a000;*/
        }

        .index-page .title {
            font-size: 6rem;
        }
        .index-page {
            background-color: #fff;
            opacity: 0.3;
        }
    </style>
<div class="container index-page">
    <div class="row">
        <div class="row2">
            <div class="content">
                <div class="title">召唤师派对</div>
            </div>
        </div>
    </div>
</div>
@endsection
