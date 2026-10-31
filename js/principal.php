<?php

session_start();

$role = $_SESSION['role'];

?>

<link rel="stylesheet" href="../assets/agenda.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.jqueryui.min.css">

<div class="menu-config">
    <button class="btn-config" onclick="toggleMenu()">
        <i class="fa-solid fa-gear"></i>
    </button>

    <div id="menuDropdown" class="menu-dropdown">

    <?php if($role != 'admin'){ ?>

        <a href="../html/perfil.php">
            <i class="fa-solid fa-user"></i>
            Meu Perfil
        </a>

    <?php } ?>

    <a href="../index.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        Voltar para o Login
    </a>

</div>
</div>
<?php if ($role == 'admin') { ?>

    <!-- ÁREA ADMIN -->

    <div id="modalDisponibilidade" class="modal">

        <div class="modal-content">

            <span class="fecharDisponibilidade">&times;</span>

            <h2>Nova Disponibilidade</h2>

            <form id="formDisponibilidade" autocomplete="off">



                <div class="campo">



                    <label>Especialidade</label>



                    <select id="especialidade_admin">



                        <option value="">

                            Selecione uma especialidade

                        </option>



                    </select>



                </div>



                <div class="campo">



                    <label>Médico</label>



                    <select id="profissional_admin">



                        <option value="">

                            Selecione um médico

                        </option>



                    </select>



                </div>


                <div class="linha-campos">

                    <div class="campo">
                        <label>Data Inicial</label>
                        <input type="date" id="data_inicio_admin">
                    </div>

                    <div class="campo">
                        <label>Data Final</label>
                        <input type="date" id="data_fim_admin">
                    </div>

                </div>
                <div class="campo">



                    <label>Dias da Semana</label>



                    <div class="dias-semana">



                        <label>

                            <input type="checkbox" name="dias[]" value="1">

                            Segunda

                        </label>



                        <label>

                            <input type="checkbox" name="dias[]" value="2">

                            Terça

                        </label>



                        <label>

                            <input type="checkbox" name="dias[]" value="3">

                            Quarta

                        </label>



                        <label>

                            <input type="checkbox" name="dias[]" value="4">

                            Quinta

                        </label>



                        <label>

                            <input type="checkbox" name="dias[]" value="5">

                            Sexta

                        </label>



                        <label>

                            <input type="checkbox" name="dias[]" value="6">

                            Sábado

                        </label> <br>



                        <label>

                            <input type="checkbox" name="dias[]" value="0">

                            Domingo

                        </label>



                    </div>



                </div>



                <div class="linha-campos">

                    <div class="campo">
                        <label>Horário</label>
                        <input type="time" id="horario_admin">
                    </div>

                    <div class="campo">
                        <label>Quantidade de Vagas</label>
                        <input type="number" id="vagas_admin" value="1" min="1">
                    </div>

                </div>



                <button

                    type="button"

                    class="btn-salvar"

                    onclick="salvarDisponibilidade()">



                    Salvar Disponibilidade



                </button>



            </form>



        </div>



    </div>



    <div class="container mt-5">



        <div class="card">



            <div class="card-header">



                <h3>Disponibilidades Cadastradas</h3>

                <button

                    type="button"

                    class="btn-cad"

                    onclick="abrirModalDisponibilidade()">



                    <i class="fa-solid fa-calendar-plus"></i>

                    Nova Disponibilidade



                </button>

            </div>



            <div class="card-body">



                <table id="tabelaDisponibilidade">



                    <thead>



                        <tr>

                            <th>Especialidade</th>

                            <th>Médico</th>

                            <th>Data</th>

                            <th>Horário</th>

                            <th>Vagas</th>

                            <th>Ação</th>

                        </tr>



                    </thead>



                    <tbody id="listarDisponibilidade">



                    </tbody>



                </table>



            </div>



        </div>



    </div>

<?php } else { ?>

    <!-- ÁREA USUÁRIO -->

    <div class="container mt-5">

        <div class="card">

            <div class="card-header">

                <h3>Meus Agendamentos</h3>

                <button
                    type="button"
                    class="btn-cad"
                    onclick="abrirModal()">

                    <i class="fa-solid fa-calendar-plus"></i>
                    Novo Agendamento

                </button>

            </div>

            <div class="card-body">

                <table id="tabela">

                    <thead>

                        <tr>
                            <th>Especialidade</th>
                            <th>Médico</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Opção</th>
                        </tr>

                    </thead>

                    <tbody id="tabelaAgendamentos">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- MODAL -->

    <div id="modalAgendamento" class="modal">

        <div class="modal-content">

            <span class="fechar">&times;</span>

            <h2>Novo Agendamento</h2>

            <form id="inserir" autocomplete="off">

                <div class="campo">

                    <label>Especialidade</label>

                    <select
                        name="especialidade_id"
                        id="especialidade_id">

                        <option value="">
                            Selecione uma especialidade
                        </option>

                    </select>

                </div>

                <div class="campo">

                    <label>Médico</label>

                    <select
                        name="profissional_id"
                        id="profissional_id">

                        <option value="">
                            Selecione um médico
                        </option>

                    </select>

                </div>

                <div class="campo">

                    <label>Data disponível</label>

                    <select
                        name="data"
                        id="data">

                        <option value="">
                            Selecione uma data
                        </option>

                    </select>

                </div>

                <div class="campo">

                    <label>Horário disponível</label>

                    <select
                        name="horario"
                        id="horario">

                        <option value="">
                            Selecione um horário
                        </option>

                    </select>

                </div>

                <button
                    type="button"
                    class="btn-salvar"
                    onclick="salvarAgendamento()">

                    <i class="fa-solid fa-calendar-plus"></i>
                    Agendar

                </button>

            </form>

        </div>

    </div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="main.js"></script>