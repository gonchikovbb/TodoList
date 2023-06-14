<!-- access задачи -->
<div class="modal fade" id="shareTasksModal{{ $tasks }}" tabindex="-1" role="dialog"
     aria-labelledby="shareTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="shareTaskModalLabel">Расшарить список</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tasks.share', $tasks) }}" method="POST" id="shareTaskForm"
                  enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="modal-body">

                    <div class="form-group">
                        <label for="status" class="col-form-label">Выберите пользователя:</label>
                        <select class="form-control" id="userIdShared" name="userIdShared" required>
                            @foreach($otherUsers as $otherUser)
                                <option value={{ $otherUser->id }}>
                                    {{$otherUser->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-form-label">Права доступа:</label>
                        <select class="form-control" id="access" name="access" required>
                        @foreach($permissions as $permission)
                                <option value="{{ $permission->code }}">{{ $permission->title }}
                                </option>
                        @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Поделиться</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
