@extends('layouts.app')

@section('content')
    
<div class="container">

  @foreach ($dataMessage as $item)
    <li>{{ $item->sujet }}</li>
  @endforeach

</div>

@endsection