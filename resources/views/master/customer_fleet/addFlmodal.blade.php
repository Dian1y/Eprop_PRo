<div class="modal fade" id="AddFleet">
	<div class="modal-dialog">
		<div class="modal-content">
		{!! Form::open(['method' => 'POST', 'action' =>'#', 'class' => 'form-horizontal']) !!}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Fleet</h4>
			</div>
			<div class="modal-body">
				@include('master.form_modal.create')
			</div>
			<div class="modal-footer">
					<a href="#" id="" class="btn btn-default submitbutton"><i class="fa fa-flash"></i>&nbsp;{{ $submitTextButton}}</a> 
					<button type="button" class="btn btn-default" data-dismiss-"modal">Close</button> 
		{!! Form::close() !!}
				<div class="success margin-top-20">
					@include('layouts.partials._message')
				</div>
			</div> 
		</div> <!-- /modal-content  -->
	</div>  <!-- /modal-dialog  -->
</div> <!-- /modal -->

