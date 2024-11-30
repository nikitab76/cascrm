@extends('Main.Layouts.sidebar')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Мои группы</h1>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success float-sm-right" data-toggle="modal"
                                data-target="#createGroupe">
                            Добавить группу
                        </button>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Modal -->
        <div class="modal fade" id="createGroupe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form role="form" method="post" id="creategroupform">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Добавить объект</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <meta name="csrf-token" content="{{ csrf_token()}}">
                            <div class="card-body">
                                <div class="form-group" id="user-container">
                                    <input type="text" name="coach" id="coach" class="form-control"
                                           value="{{\Illuminate\Support\Facades\Auth::user()->id}}"
                                           style="display: none">
                                    <label for="numGroup">Номер группы</label>
                                    <input type="number" class="form-control" id="numGroup" name="numGroup" placeholder="">

                                    <label for="users[]">Занимающиеся</label>
                                    <div class="user-row row">
                                        <div class="col-11">
                                        <select name="users[]" class="form-control" id="users">
                                            @foreach(\App\Models\Users::where('role', 'user')->get() as $user)
                                                <option value="{{$user->id}}">{{$user->fullName()}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <button type="button" class="add-btn btn btn-light" onclick="addBtn()" id="add-btn">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="createGroup()" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function addBtn(){
                const newRow = `
            <div class="user-row row mt-3">
                <div class="col-10">
                <select name="users[]" class="form-control">
                    @foreach(\App\Models\Users::where('role', 'user')->get() as $user)
                <option value="{{$user->id}}">{{$user->fullName()}}</option>
                    @endforeach
                </select>
                </div>
                <button type="button" class="add-btn btn btn-light mr-1" onclick="addBtn()" id="add-btn">+</button>
                <button type="button" class="remove-btn btn btn-danger" onclick="removeBtn()" id="remove-btn">-</button>

            </div>
        `;
                $('#user-container').append(newRow);
            }

            function removeBtn(){
                $('#user-container').on('click', '.remove-btn', function () {
                    $(this).closest('.user-row').remove();
                });
            }

            function createGroup(){
                let pageData = $('#creategroupform').serialize();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ route('groups.create') }}',
                    dataType: 'json',
                    data: pageData,
                    success: function (data) {
                        if(data.success){
                            $('#error').hide();
                            $('#success').show();
                            $('#success').text(data.error);
                            window.location.reload();
                        } else {
                            $('#success').hide();
                            $('#error').show()
                            $('#error').text(data.error)
                        }
                    },
                });
            }
        </script>
    </div>
@endsection
