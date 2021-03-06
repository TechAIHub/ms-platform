@extends('layouts.app')
@section('title') Users @endsection

@section('function')
@parent
<li class="nav-item d-none d-sm-inline-block">
  <a href="{{ route('hr.users.create') }}" class="nav-link btn btn-block btn-outline-secondary btn-sm">
    <i class="fa fa-plus"></i>
    Create
  </a>
</li>
@endsection

@section('content')
@includeIf('notification')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Administrator</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('hr.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">User list</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  
  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header cart-boder">
          <h3 class="card-title">Search</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="card-body">
          <form role="form" class="form-search">
            <div class="form-body">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {!! Form::text('email', request('email'), ['class' => 'form-control', 'placeholder' => "Enter email"]) !!}
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{ Form::label('full_name', 'Full Name') }}
                    {!! Form::text('full_name', request('full_name'), ['class' => 'form-control', 'placeholder' => "Enter full name"]) !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    {{ Form::label('roles', 'Roles') }}
                    {{ Form::select('roles[]', $roles, request('roles'), ['class' => 'select2', 'multiple' => 'multiple', 'data-placeholder' => 'Select...', 'style' => 'width: 100%;', 'name' => 'roles[]']) }}
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  {{ Form::label('status', 'Status') }}
                  <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                      {{ Form::select('status', [1 => 'Active', 0 => 'In-Active'], request('status'), ['class' => 'form-control', 'placeholder' => 'Select...', 'style' => 'width: 100%;', 'name' => 'status']) }}
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-flat ">Search</button>
                  <button type="button" class="btn btn-info btn-flat float-right">Export Excel</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <hr/>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap table-striped table-search">
                <thead>
                  <tr>
                    <th>Avatar</th>
                    <th>Email</th>
                    <th>Full name</th>
                    <th>Roles</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Birthday</th>
                    <th>Created at</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                  <tr>
                    <td>
                      @php
                        $img = ($user->avatar && Storage::disk(config('filesystems.public_disk'))->exists(config('app.upload_images_path').$user->avatar)) ? Storage::url(config('app.upload_images_path').$user->avatar) : asset('assets/images/no-image.png');
                      @endphp
                      <img src="{{ $img }}" style="height: 42px;width: 42px;border-radius: 50%;">
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{!! optional($user)->full_name !!}</td>
                    <td>
                      @foreach ($user->roles as $role)
                        <span class="badge bg-success">{{ $role->name }}</span>
                      @endforeach
                    </td>
                    <td>{!! optional($user)->gender_str !!}</td>
                    <td>
                      <span class="badge {{ $user->status ? 'bg-success' : 'bg-warning'}}">{{ $user->status_str }}</span>
                    </td>
                    <td>
                      {{ $user->birthday }}
                    </td>
                    <td>
                      {{ $user->created_at }}
                    </td>
                    <td>
                      @php
                        $roles = $user->getRoleNames()->toArray();
                      @endphp
                      <a class="btn btn-primary btn-sm btn-flat" href="{{ route('hr.users.show', $user->id) }}">
                        <i class="fas fa-eye"></i>
                        View
                      </a>
                      <a class="btn btn-info btn-sm btn-flat" href="{{ route('hr.users.edit', $user->id) }}">
                          <i class="fas fa-pencil-alt"></i>
                          Edit
                      </a>
                      @if (!in_array("Manage", $roles))
                      {!! Form::open(['url' => route('hr.users.destroy', $user->id),'method' => 'DELETE', 'class' => 'inline-block form-delete']) !!}
                        <button type="button" class="btn btn-danger btn-sm btn-flat btn-delete" data-id="{{ $user->id }}">
                          <i class="fas fa-trash"></i>
                          Delete
                        </button>
                      {!! Form::close() !!}
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="card-footer clearfix">
              <div class="row">
                <div class="col-12 col-sm-8">
                  {!! $users->appends(request()->query())->links() !!}
                </div>
                <div class="col-12 col-sm-4">
                  <span class="float-right">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection