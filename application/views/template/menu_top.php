<header>
	<?php $active=$this->uri->segment(1); ?>
			<nav class="uk-container uk-container-center uk-navbar-attached">
				<a href="#" class="uk-navbar-brand">Kenton Facturación</a>
				<ul class="uk-navbar-nav">
				<?php if($this->session->userdata('tipo')==1): ?>
					<li class="uk-parent <?php if($active=='Usuarios'){echo 'uk-active';} ?>" data-uk-dropdown>
						<a href="<?php echo base_url('Usuarios') ?>"><i class="uk-icon-users"></i> Usuarios <i class="uk-icon-caret-down"></i></a>
						<div class="uk-dropdown uk-dropdown-navbar">
			        		<ul class="uk-nav uk-nav-navbar">
			                    <li><a href="<?php echo base_url('Usuarios') ?>">Nuevo</a></li>
								<li><a href="<?php echo base_url('Usuarios/listar') ?>">Listar</a></li>
								<li><a href="#">Busqueda</a></li>
			                </ul>
			            </div>
					</li>
					<li class="uk-parent <?php if($active=='Contribuyentes'){echo 'uk-active';} ?>" data-uk-dropdown>
						<a href="<?php echo base_url('Contribuyentes') ?>"><i class="uk-icon-users"></i> Contribuyentes <i class="uk-icon-caret-down"></i></a>
						<div class="uk-dropdown uk-dropdown-navbar">
			        		<ul class="uk-nav uk-nav-navbar">
			                    <li><a href="<?php echo base_url('Contribuyentes') ?>">Nuevo</a></li>
								<li><a href="<?php echo base_url('Contribuyentes/listar') ?>">Listar</a></li>
								<li><a href="#">Busqueda</a></li>
			                </ul>
			            </div>
					</li>
				<?php else: ?>
					<li class="uk-parent <?php if($active=='facturas'){echo 'uk-active';} ?>" data-uk-dropdown>
						<a href="<?php echo base_url('facturas') ?>"><i class="uk-icon-file-text-o"></i> Facturas <i class="uk-icon-caret-down"></i></a>
						<div class="uk-dropdown uk-dropdown-navbar">
			        		<ul class="uk-nav uk-nav-navbar">
			                    <li><a href="<?php echo base_url('facturas') ?>">Nuevo CFDI</a></li>
								<li><a href="<?php echo base_url('facturas/listar') ?>">Emitidas</a></li>
								<li><a href="#">Busqueda</a></li>
			                </ul>
			            </div>
					</li>
					<li class="uk-parent <?php if($active=='clientes'){echo 'uk-active';} ?>" data-uk-dropdown>
						<a href="<?php echo base_url('clientes') ?>"><i class="uk-icon-users"></i> Clientes <i class="uk-icon-caret-down"></i></a>
						<div class="uk-dropdown uk-dropdown-navbar">
			        		<ul class="uk-nav uk-nav-navbar">
			                    <li><a href="<?php echo base_url('clientes') ?>">Nuevo</a></li>
								<li><a href="<?php echo base_url('clientes/listar') ?>">Listar</a></li>
								<li><a href="#">Busqueda</a></li>
			                </ul>
			            </div>
					</li>
					<li class="uk-parent <?php if($active=='conceptos'){echo 'uk-active';} ?>" data-uk-dropdown>
						<a href="<?php echo base_url('conceptos') ?>"><i class="uk-icon-barcode"></i> Conceptos <i class="uk-icon-caret-down"></i></a>
						<div class="uk-dropdown uk-dropdown-navbar">
			        		<ul class="uk-nav uk-nav-navbar">
			                    <li><a href="<?php echo base_url('conceptos') ?>">Nuevo</a></li>
								<li><a href="<?php echo base_url('conceptos/listar') ?>">Listar</a></li>
								<li><a href="#">Busqueda</a></li>
			                </ul>
			            </div>
					</li>
					<li class="uk-parent <?php if($active=='configuracion'){echo 'uk-active';} ?>" data-uk-dropdown>
						<a href="<?php echo base_url('configuracion'); ?>"><i class="uk-icon-cog"></i> Configuración <i class="uk-icon-caret-down"></i></a>
						<div class="uk-dropdown uk-dropdown-navbar">
							<ul class="uk-nav uk-nav-navbar">
								<li><a href="<?php echo base_url('configuracion'); ?>">Datos fiscales</a></li>
								<li><a href="<?php echo base_url('configuracion/series'); ?>">Series</a></li>
							</ul>
						</div>
					</li>
				<?php endif; ?>
				</ul>
				<div class="uk-navbar-flip">
					<a href="<?php echo base_url('contribuyentes/perfil'); ?>">
						<i class="uk-icon-user"></i>  
						<?php echo $this->session->userdata('email'); ?>
					</a>
					|
					<a href="<?php echo base_url('logout'); ?>">
						<i class="uk-icon-sign-out"></i> Salir
					</a>
			    </div>
			</nav>
		</header>
