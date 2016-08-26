<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>

            <div class="modal-body">
                <p>Confirm to delete.<p>
            </div>

            <div class="modal-footer deleteAction">
                {!! Form::open(['id' => 'deleteForm']) !!}
                    {!! Form::button('Cancel', ['class' => 'btn btn-success', 'data-dismiss' => 'modal']) !!}
                    {!! Form::button('Delete', ['class' => 'btn btn-danger deleteButton', 'data-dismiss' => 'modal']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>