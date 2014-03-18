<div class="uk-width-2-10" id="izq">
	<ul class="uk-nav uk-nav-parent-icon uk-nav-side" data-uk-nav>
	<?php $active=$this->uri->segment(1);?>
		<?php if($this->session->userdata('tipo')==1): //cuando es 'usuario' mostrar solo su nonmbre ?>
		<li>
        	<a href="#"><i class="uk-icon-user"></i> <?php $user=explode("@", $this->session->userdata('email')); echo $user[0]; ?></a>
		</li>
		<li class="uk-nav-divider"></li>
		<li class="uk-parent <?php if($active=='usuarios'){echo 'uk-active';} ?>">
			<a href="#"><i class="uk-icon-group"></i> Usuarios</a>
			<ul class="uk-nav-sub">
				<li><a href="<?php echo base_url('usuarios'); ?>">Nuevo</a></li>
				<li><a href="<?php echo base_url('usuarios/listar'); ?>">Listar</a></li>
			</ul>
		</li>
		<li class="uk-parent <?php if($active=='contribuyentes'){echo 'uk-active';} ?>">
			<a href="#"><i class="uk-icon-group"></i> Emisores</a>
			<ul class="uk-nav-sub">
				<li><a href="<?php echo base_url('contribuyentes'); ?>">Nuevo</a></li>
				<li><a href="<?php echo base_url('contribuyentes/listar'); ?>">Listar</a></li>
			</ul>
		</li>
		<?php else: //si es 'emisor' mostrar enlaces a asus datos fiscales?>
		<li class="uk-parent">
        	<a href="#"><i class="uk-icon-user"></i> <?php $user=explode("@", $this->session->userdata('email')); echo $user[0]; ?></a>
            <ul class="uk-nav-sub">
            	<li><a href="<?php echo base_url('contribuyentes/perfil'); ?>">Mis Datos</a></li>
                <li><a href="<?php echo base_url('logout'); ?>">Salir</a></li>
			</ul>
		</li>
        <li class="uk-nav-divider"></li>
		<li <?php if($active=='facturacion'){echo 'class="uk-active"';} ?>>
			<a href="<?php echo base_url('facturacion'); ?>"><i class="uk-icon-home"></i> Inicio</a>
		</li>
		<li class="uk-parent <?php if($active=='factura'){echo 'uk-active';} ?>">
			<a href="#"><i class="uk-icon-file-text-o"></i> Facturas</a>
			<ul class="uk-nav-sub">
				<li><a href="<?php echo base_url('factura') ?>">Nueva</a></li>
				<li><a href="<?php echo base_url('factura/emitidas') ?>">Emitidas</a></li>
			</ul>
		</li>
		<li class="uk-parent <?php if($active=='clientes'){echo 'uk-active';} ?>">
			<a href="#"><i class="uk-icon-group"></i> Clientes</a>
			<ul class="uk-nav-sub">
				<li><a href="<?php echo base_url('clientes') ?>">Nuevo</a></li>
				<li><a href="<?php echo base_url('clientes/listar') ?>">Listar</a></li>
			</ul>
		</li>
		<li class="uk-parent <?php if($active=='conceptos'){echo 'uk-active';} ?>">
			<a href="#"><i class="uk-icon-barcode"></i> Conceptos</a>
			<ul class="uk-nav-sub">
				<li><a href="<?php echo base_url('conceptos') ?>">Nuevo</a></li>
				<li><a href="<?php echo base_url('conceptos/listar') ?>">Listar</a></li>
			</ul>
		</li>
		<li class="uk-parent <?php if($active=='configuracion'){echo 'uk-active';} ?>">
			<a href="#">
				<i class="uk-icon-cog"></i> Configuración
			</a>
			<ul class="uk-nav-sub">
				<li><a href="<?php echo base_url('configuracion'); ?>">Datos fiscales</a></li>
				<li><a href="<?php echo base_url('configuracion/series'); ?>">Series</a></li>
			</ul>
		</li>
		<?php endif; ?>
        <li class="uk-nav-divider"></li>
	</ul>
    <div class="uk-text-center">San Quintín BC<br>2014</div>
</div>