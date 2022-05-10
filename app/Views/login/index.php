<?= $this->extend('loginLayout') ?>
<?= $this->section('content') ?>
<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                <div class="d-flex justify-content-center py-4">
                    <a href="/" class="logo d-flex align-items-center w-auto">
                        <img src="/assets/templates/NiceAdmin/assets/img/logo.png" alt="Logo">
                        <span class="d-none d-lg-block"><?= config('App')->appName ?></span>
                    </a>
                </div><!-- End Logo -->

                <div class="card mb-3">

                    <div class="card-body">
                        <div class="pt-4 pb-2">
                            <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                            <p class="text-center small">Informe email e senha</p>
                        </div>

                        <form class="row g-3 needs-validation" method="post" novalidate>

                            <div class="col-12">
                                <label for="usuario" class="form-label">E-mail</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrependEmail">@</span>
                                    <input type="text" name="email" class="form-control" id="email" value="<?= set_value('email') ?>" required>
                                    <div class="invalid-feedback">Por favor, informe seu email!</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="senha" class="form-label">Senha</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrependsenha"><i class='bi bi-key'></i></span>
                                    <input type="password" name="senha" class="form-control" id="senha" value="<?= set_value('senha') ?>" required>
                                    <div class="invalid-feedback">Por favor, informe sua senha!</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">Login</button>
                            </div>
                            <div class="col-12">
                                <p class="small mb-0">Ainda não tem conta? <a href="cadastro">Cadastre-se</a></p>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="credits text-light">
                    <!-- All the links in the footer should remain intact. -->
                    <!-- You can delete the links only if you purchased the pro version. -->
                    <!-- Licensing information: https://bootstrapmade.com/license/ -->
                    <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                    Designed by <a class=' text-light' href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>

            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>