@extends('layouts.client')

@section('title', trans('main.Solutions') . ' | ' . $group->name)

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12 col-sm-12">
        {{-- group导航栏 --}}
        <x-group.navbar :group-id="$group->id" :group-name="$group->name" />
      </div>
      <div class="col-12 col-sm-12">
        <div class="my-container bg-white">
          @livewire('solution.solutions', ['groupId' => $group->id])
        </div>
      </div>
    </div>
  </div>
@endsection
