<div class="modal fade" id="cesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>

            <div class="modal-body">
                <div id="form-errors">
                </div>

                <div class="row cesForm"></div>
            </div>

            <div class="modal-footer ceActions">
                {!! Form::button('Save', ['class' => 'btn btn-success saveButton']) !!}
                {!! Form::button('Cancel', ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) !!}
            </div>
        </div>
    </div>
</div>