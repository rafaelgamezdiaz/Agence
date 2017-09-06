@extends('layouts.app')
@section('title','Controle de Atividades Online - Agente Interativa')
@section('content')
	<div class="container big-padding">
		<div class="row">
			<div class="tabs">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#consultor" data-toggle="tab"><i class="fa fa-user-o"></i> <strong>Por Consultor</strong></a>
					</li>
					<li>
						<a href="#cliente" data-toggle="tab"><i class="fa fa-user-circle-o"></i> <strong>Por Cliente</strong></a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="consultor" class="tab-pane active">
						  <form class="form-horizontal" id="formu" action="{{ route('comercial.relatorio') }}" action2="{{ route('comercial.grafico') }}" action3="{{ route('comercial.pizza') }}" novalidate="novalidate">
						  	<input name="_token" type="hidden" value = "{{ csrf_token() }}" id="token">
						  	<div class="row">
						  		<div class="col-xs-12 col-md-8">
								  	<div class="form-group">
										<label class="col-md-3 control-label"><strong>Consultores</strong></label>
										<div class="col-md-9">
											<div class="input-group btn-group">
												<span class="input-group-addon">
													<i class="fa fa-users"></i>
												</span>
												<select class="form-control" name="lista" multiple="multiple" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' id="lista" style="width: 100%">
													@foreach($consultores as $consultor)
														<option value="{{ $consultor->co_usuario }}" >{{ $consultor->no_usuario }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="form-group"> 
										<label class="col-md-3 control-label"><strong>Período</strong></label>
										<div class="col-md-4">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i> Desde
												</span>
												<input name="fecha" value="" id="date1" data-plugin-masked-input data-input-mask="99/9999" placeholder="__/____" class="form-control">
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i> Hasta
												</span>
												<input name="fechafin" id="date2" data-plugin-masked-input data-input-mask="99/9999" placeholder="__/____" class="form-control">
											</div>
										</div>
									</div>
						  		</div>
						  		<div class="col-xs-12 col-md-4">
						  			<button type="button" id="btnRelatorio" class="btn btn-warning btn-block"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;Relat&oacute;rio</button>
						  			<button type="button" id="btnGrafico" class="btn btn-info btn-block"><i class="icons icon-graph"></i>&nbsp;Gr&aacute;fico</button>
						  			<button type="button" id="btnPizza" class="btn btn-danger btn-block"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp;Pizza</button>
						  		</div>
						  	</div>
						  	<br>
			              </form>

			              <!-- Resultados Relatorio-->
							<div class="row">
								<div class="col-xs-12" style="min-height: 50px;">
									<div id="cargando" style="display:none">
										<div data-loading-overlay data-loading-overlay-options='{ "startShowing": true }' style="min-height: 50px;">
										</div>
									</div>
								</div>
								<div class="col-xs-12">
									<!-- La tabla de resultados se carga con Ajax -->
									<div id="resultRelatorio">
									</div>
								</div>
								<div class="col-xs-12 col-md-8 col-md-offset-2">
									<div id="graficos" >
										
									</div>
								</div>
							</div>
					</div>
					<div id="cliente" class="tab-pane">
						
					</div>
				</div>
			</div>
		</div>	
	</div>
@endsection
@section('js')
	<!--script src="{{ asset('js/popcalendar.js') }}"></script-->
    <script src="{{ asset('js/theme.custom.js') }}"></script>
    <script src="{{ asset('js/cor_fundo.js') }}"></script>
    <script src="{{ asset('js/ui-elements/examples.loading.overlay.js') }}"></script>

    <script src="{{ asset('vendor/flot/jquery.flot.js') }}"></script>
	<script src="{{ asset('vendor/flot.tooltip/flot.tooltip.js') }}"></script>
	<script src="{{ asset('vendor/flot/jquery.flot.pie.js') }}"></script> 
	<script src="{{ asset('vendor/flot/jquery.flot.categories.js') }}"></script>
	<script src="{{ asset('vendor/flot/jquery.flot.resize.js') }}"></script>
@endsection