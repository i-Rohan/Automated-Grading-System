@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label for="user_id" class="col-md-4 control-label">ID</label>
                                <div class="col-md-6">
                                    <input id="user_id" type="text" class="form-control" name="user_id"
                                           value="{{ old('user_id') }}"
                                           required autofocus>
                                    @if ($errors->has('user_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{ old('name') }}" required>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="col-md-4 control-label">Username</label>
                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username"
                                           value="{{ old('username') }}" required>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}" required>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="authority_level" class="col-md-4 control-label">Authority Level</label>
                                <div class="col-md-6">
                                    <label>
                                        <select name="authority_level" class="form-control"
                                                value="{{ old('authority_level') }}" required>
                                            <option value=""></option>
                                            <option value="admin">Admin</option>
                                            <option value="teacher">Teacher</option>
                                            <option value="student">Student</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="batch" class="col-md-4 control-label">Batch</label>
                                <div class="col-md-6">
                                    <label>
                                        <select name="batch" class="form-control"
                                                value="{{ old('batch') }}" required>
                                            <option value=""></option>
                                            <option value="">N/A</option>
                                            <option value="2014">2014</option>
                                            <option value="2015">2015</option>
                                            <option value="2016">2016</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sem" class="col-md-4 control-label">Semester</label>
                                <div class="col-md-6">
                                    <label>
                                        <select name="sem" class="form-control"
                                                value="{{ old('sem') }}" required>
                                            <option value=""></option>
                                            <option value="">N/A</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="discipline" class="col-md-4 control-label">Discipline</label>
                                <div class="col-md-6">
                                    <label>
                                        <select id="discipline" name="discipline" class="form-control"
                                                value="{{ old('discipline') }}" required>
                                            <option value=""></option>
                                            <option value="">N/A</option>
                                            <option value="btech">B.Tech</option>
                                            <option value="bba">BBA</option>
                                            <option value="bcom">B.Com</option>
                                            <option value="mba">MBA</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('stream') ? ' has-error' : '' }}">
                                <label for="stream" class="col-md-4 control-label">Stream</label>
                                <div class="col-md-6">
                                    <label>
                                        <select id="stream" name="stream" class="form-control"
                                                value="{{ old('stream') }}" required>
                                            <option value=""></option>
                                            <option value="">N/A</option>
                                            <option value="csc">CSC</option>
                                            <option value="cse">CSE</option>
                                            <option value="me">ME</option>
                                            <option value="ece">ECE</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
