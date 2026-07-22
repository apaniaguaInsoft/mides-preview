<?php
/**
 * Template Name: MIDES Trámites
 * Template Post Type: page
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$linea_svg = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';

get_header();
?>

<!-- HERO -->
<section class="contacto-hero">
  <div class="contacto-hero__overlay"></div>
  <div class="container contacto-hero__content">
    <h1 class="contacto-hero__title">Trámites Simplificados</h1>
    <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="contacto-hero__line" />
    <p class="contacto-hero__sub">Accede a los servicios y trámites en línea del Ministerio de Desarrollo Social</p>
  </div>
</section>

<!-- TRÁMITES -->
<section class="tramites-section">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title">Servicios <span class="title-highlight">Digitales<img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" /></span></h2>
    </div>
    <p class="tramites-intro__text">Realiza y consulta tus solicitudes de forma rápida y segura desde cualquier dispositivo. Selecciona la categoría que necesitas.</p>

    <div class="tramites-grid">

      <!-- General -->
      <div class="tramite-card">
        <div class="tramite-card__icon">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        </div>
        <div class="tramite-card__body">
          <span class="tramite-card__cat">General</span>
          <p class="tramite-card__desc">Consulta el catálogo completo de trámites simplificados disponibles en el MIDES.</p>
          <div class="tramite-card__actions">
            <a href="https://tramites.mides.gob.gt/Vista/Tramites/frmCatalogoTramites.aspx" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo mides_tramite_link_icon(); ?>
              Listado de Trámites Simplificados
            </a>
          </div>
        </div>
      </div>

      <!-- FODES -->
      <div class="tramite-card">
        <div class="tramite-card__icon tramite-card__icon--amber">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div class="tramite-card__body">
          <span class="tramite-card__cat">FODES</span>
          <p class="tramite-card__desc">Fondo de Desarrollo Social — presenta quejas o ingresa solicitudes a programas internos.</p>
          <div class="tramite-card__actions">
            <a href="https://fodes.gob.gt/formato-de-queja/" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo mides_tramite_link_icon(); ?>
              Formato de Queja
            </a>
            <a href="https://fodes.gob.gt/programas-internos/" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo mides_tramite_link_icon(); ?>
              Ingresar Solicitud
            </a>
          </div>
        </div>
      </div>

      <!-- Recursos Humanos -->
      <div class="tramite-card">
        <div class="tramite-card__icon">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div class="tramite-card__body">
          <span class="tramite-card__cat">Recursos Humanos</span>
          <p class="tramite-card__desc">Ingresa o da seguimiento a solicitudes relacionadas con el área de Recursos Humanos del MIDES.</p>
          <div class="tramite-card__actions">
            <a href="https://tramites.mides.gob.gt/Vista/Solicitud/RRHH/frmSolicitudRRHH.aspx" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo mides_tramite_link_icon(); ?>
              Ingresar Solicitud
            </a>
            <a href="https://tramites.mides.gob.gt/Vista/Solicitud/RRHH/frmConsultaSolicitudRRHH.aspx" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo mides_tramite_link_icon(); ?>
              Consultar Solicitud
            </a>
          </div>
        </div>
      </div>

      <!-- Información Pública -->
      <div class="tramite-card">
        <div class="tramite-card__icon tramite-card__icon--amber">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="11" cy="14" r="3"/><line x1="16" y1="19" x2="13.5" y2="16.5"/></svg>
        </div>
        <div class="tramite-card__body">
          <span class="tramite-card__cat">Información Pública</span>
          <p class="tramite-card__desc">Consulta el estado de tu solicitud de acceso a la información pública enviada al MIDES.</p>
          <div class="tramite-card__actions">
            <a href="https://infopublica.mides.gob.gt/pages/request/check-request" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo mides_tramite_link_icon(); ?>
              Consultar Solicitud
            </a>
          </div>
        </div>
      </div>

      <!-- Consultas y Reclamos — ancho completo -->
      <div class="tramite-card tramite-card--full">
        <div class="tramite-card__icon">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        </div>
        <div class="tramite-card__body">
          <span class="tramite-card__cat">Consultas y Reclamos</span>
          <p class="tramite-card__desc">¿Tienes alguna queja o reclamo? Ingresa tu caso o revisa el estado de una gestión previamente registrada.</p>
          <div class="tramite-card__actions tramite-card__actions--row">
            <a href="https://atencionyconsultas.mides.gob.gt/Vista/wf_ingreso_reclamo.aspx" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo mides_tramite_link_icon(); ?>
              Ingresar Solicitud
            </a>
            <a href="https://atencionyconsultas.mides.gob.gt/Vista/wf_consultar_gestion.aspx" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo mides_tramite_link_icon(); ?>
              Consultar Solicitud
            </a>
          </div>
        </div>
      </div>

    </div>

    <!-- Nota informativa -->
    <div class="tramites-note">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <p>Para realizar trámites necesitarás tu número de DPI. Si tienes inconvenientes, comunícate a nuestro PBX: <strong>+502 2300-5400</strong> en horario de lunes a viernes de 8:00 a 16:00 horas.</p>
    </div>

  </div>
</section>

<?php get_footer(); ?>
