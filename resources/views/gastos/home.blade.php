@extends('layouts.app_layout')

@section('modulo')
<div class="container">
    <div class="row">
        <!-- <div class="h2">Dashboard</div> -->
    </div>

    <!-- ------------------------- CARDS ------------------------- -->
    <x-orm.cards-container :cards="$cards" />
</div>
@endsection
