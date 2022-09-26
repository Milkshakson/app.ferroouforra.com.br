<aside id="sidebar" class="sidebar">
<img src="/assets/templates/NiceAdmin/assets/img/logo.png" />
  <hr class='divider' />
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Geral</li>
    <li class="nav-item"><a class="nav-link " href="<?=site_url()?>"> <i class="bx bx-home"></i> <span><?=config('App')->appSigla ?>-Início</span>
    </a></li>
    <?php if(session('isValidTokenAcesso')){?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="/session/current">
          <i class="bi bi-envelope"></i>
          <span>Minha sessão</span>
        </a>
      </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="/login/logout">
          <i class="bx bx-exit"></i>
          <span>Sair</span>
        </a>
      </li>
      <?php }?>
  </ul>
</aside>