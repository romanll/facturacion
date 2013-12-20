					<ul class="uk-nav uk-nav-parent-icon uk-nav-side" data-uk-nav>
						<li>
							<a href="<?php echo base_url('contribuyentes'); ?>">
								<i class="uk-icon-user"></i>  
								<?php $user=explode("@", $this->session->userdata('correo')); echo $user[0]; ?>
							</a>
						</li>
						<li class="uk-nav-divider"></li>
						<li class="uk-parent uk-active">
							<a href="#"><i class="uk-icon-group"></i> Usuarios</a>
							<ul class="uk-nav-sub">
								<li><a href="<?php echo base_url('usuarios'); ?>">Nuevo</a></li>
								<li><a href="<?php echo base_url('usuarios/listar'); ?>">Listar</a></li>
								<li><a href="#">Busqueda</a></li>
							</ul>
						</li>
						<li class="uk-parent">
							<a href="#"><i class="uk-icon-book"></i> Clientes</a>
							<ul class="uk-nav-sub">
								<li><a href="<?php echo base_url('clientes') ?>">Nuevo</a></li>
								<li><a href="<?php echo base_url('clientes/listar') ?>">Listar</a></li>
								<li><a href="#">Busqueda</a></li>
							</ul>
						</li>
						<li class="uk-parent">
							<a href="#"><i class="uk-icon-barcode"></i> Conceptos</a>
							<ul class="uk-nav-sub">
								<li><a href="<?php echo base_url('conceptos') ?>">Nuevo</a></li>
								<li><a href="<?php echo base_url('conceptos/listar') ?>">Listar</a></li>
								<li><a href="#">Busqueda</a></li>
							</ul>
						</li>
						<li class="uk-nav-divider"></li>
						<li>
							<a href="<?php echo base_url('logout'); ?>">
								<i class="uk-icon-signout"></i> Salir
							</a>
						</li>
					</ul>