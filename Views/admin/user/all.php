<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = session();
$permissions = $session->get('permissions');

?>
<!DOCTYPE html>
<style>
    .dropdown-menu {
        top: auto;
    }

    .dropdown-item {
        padding: .10rem 1.5rem;
    }

    .dataTables_empty {
        text-align: center;
    }

    .select2-selection {
        overflow: hidden !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex">
        <div class="card flex-fill p-3">

            <div class="row align-items-center col-12 mb-3">
                <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                    <h3 class="mb-0">Usuários</h3>
                </div>
                <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-3">
                    <a href="#add_users" class="btn bg-orange text-white float-end" style="white-space:nowrap;">Adicionar Usuário</a>
                </div>
            </div>

            <table id="datatables-dashboard-projects" class="table table-striped my-0 ">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th class="d-none d-md-table-cell">E-mail</th>
                        <th scope="col">CPF/CNPJ</th>
                        <th scope="col">Print Eye Trancking</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($users)) {
                        foreach ($users as $user) { ?>
                            <tr>
                                <td><?php echo $user["name"] ?></td>
                                <td class="d-none d-md-table-cell"><?php echo $user["email"] ?></td>
                                <td><?php echo $user["cpf_cnpj"] ?></td>
                                <?php if (!empty($user["print_eye_tracking"])) { ?>
                                    <td><a target="__blank" href="<?php echo base_url($user["print_eye_tracking"]) ?>"><img style="width: 30px;" src="<?php echo base_url($user["print_eye_tracking"]) ?>"></img></a></td>
                                <?php } else { ?>
                                    <td></td>
                                <?php } ?>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only" href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="<?php echo base_url('index.php/edit_client/' . $user['id']) ?>">Editar usuário</a>
                                            <a class="dropdown-item" onclick="remove_user(this)" href="#" data-id="<?php echo $user['id'] ?>" data-name="<?php echo $user['name'] ?>" data-bs-toggle="modal" data-bs-target="#delete">Excluir usuário</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal confirmação ao deletar usuário -->
                            <div class="modal fade" id="delete" tabindex="-1" role=" " aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Deletar usuário</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body m-3">
                                            <div class="text-center">
                                                <h4 id="certeza" class="mb-4"></h4>
                                                <button type="button" class="btn btn-success" onclick="deleta(this)" data-id="">SIM</button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">NÃO</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="card-header" style="background-color: #fc9700;">
                <h3 class="mt-1 text-white">Cadastrar Usuário</h3>
            </div>
            <div class="card-body" id="add_users">
                <form role="form" method="post" action="<?php echo base_url('index.php/add_user') ?>">
                    <div class="row mb-4">
                        <h6 class="card-subtitle text-muted mt-2">Informações da versão</h6>
                        <hr class="mt-2" />
                        <div class="col-lg-3">
                            <div class="form-group3">
                                <label class="mt-2 form-control-label">Nome</label>
                                <input type="text" name="name" class="mt-2 form-control form-control-alternative" placeholder="Nome Completo" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="mt-2 form-control-label">E-mail</label>
                                <input type="email" name="email" class="mt-2 form-control form-control-alternative" placeholder="E-mail do usuário" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="mt-2 form-control-label">Senha</label>
                                <input type="password" name="pass" class="mt-2 money form-control form-control-alternative" placeholder="Senha do usuário" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="shelves">CPF/CNPJ</label>
                                <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="mt-2 money form-control form-control-alternative" placeholder="CPF/CNPJ">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <h6 class="mt-1 card-subtitle text-muted ">Permissões ao usuário</h6>
                        <hr class="mt-2">
                        <div class="col-12">
                            <div id="boxPermission" class="form-group">
                                <label class="mt-2 form-control-label">Atribuir/Alterar as permissões do usuário</label>
                                <select id="selectPermission" multiple class="form-control" name="permissionUser" placeholder="Selecione uma permission">
                                    <?php foreach ($all_permissions as $permission) { ?>
                                        <option value="<?php echo $permission["id"] ?>"><?php echo $permission["permission_name"]  ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="btn bg-orange text-white">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $("#selectPermission").select2({
        placeholder: "Selecione uma Permissão",
        allowClear: true,
    });

    function remove_user(user) {
        let id = $(user).data('id');
        $('.btn-success').attr('data-id', id);
        let name = $(user).data('name');
        $('#certeza').text('Tem certeza que deseja deletar  ' + name + ' ?');
    }

    function deleta(user) {
        let id = $(user).data('id');
        window.location.href = "<?php echo base_url('index.php/delete_user') ?>" + "/" + id;
    }

    $(function() {
        $('#datatables-dashboard-projects').DataTable({});
    });
</script>
<script type="text/javascript" src="<?php echo base_url("assets/js/simditor/simditor.js") ?>"></script>
