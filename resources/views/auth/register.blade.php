@extends('layouts.app')

@section('content')
    <?php
    // Function to get the client IP address
    function get_client_ip_1()
    {
        if (getenv('HTTP_CLIENT_IP'))
            $ipAddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipAddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipAddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipAddress = getenv('REMOTE_ADDR');
        else
            $ipAddress = 'UNKNOWN';
        return $ipAddress;
    }
    function get_client_ip_2()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    ?>
    @if(get_client_ip_1()=='127.0.0.1' or get_client_ip_1()=='::1')
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Register</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                                {{csrf_field()}}

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
                                        <input id="password" type="password" class="form-control" name="password"
                                               required>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password-confirm" class="col-md-4 control-label">Confirm
                                        Password</label>
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
    @else
        <div align="center" style="margin-top: 100px;margin-bottom: 50px">
            <img src="{{URL::to('/')}}/images/403.gif" style="margin: 10px">
            <img src="{{URL::to('/')}}/images/403_2.gif" style="margin: 10px">
            <img src="{{URL::to('/')}}/images/403_3.gif" style="margin: 10px">
            <img src="{{URL::to('/')}}/images/403_4.gif" style="margin: 10px">
        </div>
    @endif
@endsection
