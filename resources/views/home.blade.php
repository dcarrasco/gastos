@extends('layouts.app_layout')

@section('modulo')
<div class="container">
    <div class="row">
        <!-- <div class="h2">Dashboard</div> -->
    </div>

    <!-- ------------------------- CARDS ------------------------- -->
    @include('orm.components.cards_container')

</div>
@endsection

