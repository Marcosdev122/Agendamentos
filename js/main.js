$(document).ready(function () {

    carregarAgendamentos();

    // Só chama se existir a tabela do médico
    if ($("#tabelaDisponibilidade").length) {
        carregarDisponibilidade();
    }

    $('#cpf').on('input', function () {

        let value = $(this).val().replace(/\D/g, '');

        value = value.substring(0, 11);
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        $(this).val(value);

    });

});

function gerarAgenda() {

    let data = $('#data_agenda').val();
    let inicio = $('#hora_inicio').val();
    let fim = $('#hora_fim').val();
    let duracao = parseInt($('#duracao').val());
    let vagas = $('#vagas').val();

    let html = '';

    let atual = new Date(`2000-01-01 ${inicio}`);
    let limite = new Date(`2000-01-01 ${fim}`);

    while (atual <= limite) {

        let hora =
            String(atual.getHours()).padStart(2, '0')
            + ':'
            +
            String(atual.getMinutes()).padStart(2, '0');

        html += `
            <tr>
                <td>${data}</td>
                <td>${hora}</td>
                <td>${vagas}</td>
            </tr>
        `;

        atual.setMinutes(
            atual.getMinutes() + duracao
        );
    }

    $('#previewAgenda').html(html);

}

$(document).on("change", "#profissional_id", function () {

    $("#data").html('<option value="">Selecione uma data</option>');
    $("#horario").html('<option value="">Selecione um horário</option>');
    let profissional = $(this).val();

    $.ajax({
        url: "../Sessions/buscar_datas.php",
        type: "GET",
        data: {
            profissional_id: profissional
        },
        dataType: "json",
        success: function (datas) {

            let html = '<option value="">Selecione uma data</option>';

            datas.forEach(function (data) {

                html += `
        <option value="${data.data}">
            ${data.texto}
        </option>
    `;

            });

            $("#data").html(html);
        }
    });

});
$(document).on("change", "#data", function () {

    $("#horario").html('<option value="">Selecione um horário</option>');

    let profissional = $("#profissional_id").val();
    let data = $(this).val();

    $.ajax({
        url: "../Sessions/buscar_horarios.php",
        type: "GET",
        data: {
            profissional_id: profissional,
            data: data
        },
        dataType: "json",
        success: function (horarios) {
            console.log(horarios);
            let html = '<option value="">Selecione um horário</option>';

            horarios.forEach(function (horario) {

                html += `
            <option value="${horario.horario}">
                ${horario.texto}
            </option>
                       `;
            });

            $("#horario").html(html);
        }
    });

});
$(document).on("change", "#especialidade_admin", function () {

    let especialidade = $(this).val();

    $.ajax({

        url: "../Sessions/buscar_medicos_admin.php",
        type: "GET",

        data: {
            especialidade_id: especialidade
        },

        dataType: "json",

        success: function (medicos) {

            let html = `
                <option value="">
                    Selecione um médico
                </option>
            `;

            medicos.forEach(function (medico) {

                html += `
                    <option value="${medico.id}">
                        ${medico.nome}
                    </option>
                `;

            });

            $("#profissional_admin").html(html);

        }

    });

});
$(document).on("change", "#especialidade_id", function () {

    $("#profissional_id").html('<option value="">Selecione um médico</option>');
    $("#data").html('<option value="">Selecione uma data</option>');
    $("#horario").html('<option value="">Selecione um horário</option>');


    let especialidade = $(this).val();

    $.ajax({

        url: "../Sessions/buscar_medicos.php",

        type: "GET",

        data: {
            especialidade_id: especialidade
        },

        dataType: "json",

        success: function (medicos) {

            let html = `
                <option value="">
                    Selecione um médico
                </option>
            `;

            medicos.forEach(function (medico) {

                html += `
                    <option value="${medico.id}">
                        ${medico.nome}
                    </option>
                `;

            });

            $("#profissional_id").html(html);

        }

    });

});
function carregarEspecialidadesAdmin() {

    $.ajax({

        url: "../Sessions/buscar_especialidades.php",
        type: "GET",
        dataType: "json",

        success: function (dados) {

            let html = `
                <option value="">
                    Selecione uma especialidade
                </option>
            `;

            dados.forEach(function (esp) {

                html += `
                    <option value="${esp.id}">
                        ${esp.especialidade}
                    </option>
                `;

            });

            $("#especialidade_admin").html(html);

        }

    });

}
$(document).ready(function () {

    carregarEspecialidadesAdmin();

});

function carregarEspecialidades() {

    $.ajax({

        url: "../Sessions/buscar_especialidades.php",

        type: "GET",

        dataType: "json",

        success: function (dados) {

            let html = `
                <option value="">
                    Selecione sua especialidade
                </option>
            `;

            dados.forEach(function (esp) {

                html += `
                    <option value="${esp.id}">
                        ${esp.especialidade}
                    </option>
                `;

            });

            $("#especialidade_id").html(html);

        }

    });

}
function verPaciente(nome, email, cpf) {

    Swal.fire({
        title: 'Dados do Paciente',
        html: `
            <b>Nome:</b> ${nome}<br>
            <b>Email:</b> ${email}<br>
            <b>CPF:</b> ${cpf}
        `,
        icon: 'info',
        confirmButtonText: 'Fechar'
    });

}
function cadastrar() {
    let cpf = $('#cpf').val().replace(/\D/g, '');

    if (cpf.length != 11) {
        Swal.fire({
            icon: 'warning',
            title: 'CPF inválido!',
            text: 'O CPF deve conter 11 dígitos.'
        });
        return;
    }
    let sexo = $('input[name="sexo"]:checked').val();

    if (!sexo) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Selecione o sexo.'
        });
        return;
    }
    const form = document.getElementById('cadastro');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    const formData = new FormData(form);

    $.ajax({
        url: '../Sessions/cadastro.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,

        success: function (response) {

            console.log(response);

            if (response.trim() == "success") {

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Cadastro realizado com sucesso!'
                }).then(() => {
                    window.location.href = '../index.php';
                });

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: response
                });

            }

        }
    });

}
function login() {

    const form = document.getElementById("login");
    const formData = new FormData(form);

    const btn = document.getElementById("btnLogin");

    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner"></span>
        Carregando...
    `;
    let loginSucesso = false;

    $.ajax({
        url: "Sessions/verif.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",

        beforeSend: function () {
            btn.disabled = true;
            btn.innerHTML = `
            <span class="spinner"></span>
            Carregando...
        `;
        },



        success: function (response) {

            if (response.status === "success") {

                loginSucesso = true;

                const nome = response.nome.length > 10
                    ? response.nome.substring(0, 10) + "..."
                    : response.nome;

                Swal.fire({
                    icon: "success",
                    title: `Bem-vindo, ${nome}!`,
                }).then(() => {
                    window.location.href = "js/principal.php";
                });

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: response.message
                });

            }
        },

        complete: function () {

            if (!loginSucesso) {
                btn.disabled = false;
                btn.innerHTML = "Entrar";
            }

        }
    });


    window.addEventListener("pageshow", function () {

        const btn = document.getElementById("btnLogin");

        btn.disabled = false;
        btn.innerHTML = `
        Entrar
    `;

    });
}
$('#formPerfil').submit(function (e) {

    e.preventDefault();

    $.ajax({

        url: '../Sessions/salvar_perfil_medico.php',

        type: 'POST',

        data: {
            nome: $('#nome').val(),
            especialidade_id: $('#especialidade_id').val()
        },

        success: function (response) {

            if (response == "success") {

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: 'Perfil cadastrado'
                }).then(() => {

                    location.reload();

                });

            }

        }

    });

});
$(function () {
    $('#cpf').mask('000.000.000-00');
});

function alterarSenha() {

    const form = document.getElementById("password_reset");

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    $.ajax({

        url: "../Sessions/password_reset.php",

        type: "POST",

        data: new FormData(form),

        processData: false,

        contentType: false,

        success: function (response) {

            if (response.trim() == "success") {

                Swal.fire({
                    icon: "success",
                    title: "Sucesso",
                    text: "Senha alterada com sucesso!"
                }).then(() => {

                    window.location.href = "../index.php";

                });

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Erro",
                    text: response
                });

            }

        }

    });

}
function carregarAgendamentos() {

    $.ajax({
        url: 'buscar_agendamentos.php',
        type: 'GET',

        success: function (html) {

            if ($.fn.DataTable.isDataTable('#tabela')) {
                $('#tabela').DataTable().destroy();
            }

            $('#tabelaAgendamentos').html(html);

            $('#tabela').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/pt-BR.json'
                }
            });

        }
    });

}

function abrirModal() {

    $('#modalAgendamento').css('display', 'flex');
    carregarEspecialidades();
}
$(document).on("click", ".fechar", function () {
    $("#modalAgendamento").hide();
    location.reload();
});

// FECHAR CLICANDO FORA DO MODAL
$(window).click(function (e) {

    if ($(e.target).is("#modalAgendamento")) {
        $("#modalAgendamento").hide();
        location.reload();
    }

});
//Abrir e Fechar Modal do ADM
function abrirModalDisponibilidade() {

    $('#formDisponibilidade')[0].reset();

    $('#profissional_admin').html(`
        <option value="">
            Selecione um médico
        </option>
    `);

    $('#modalDisponibilidade').css('display', 'flex');

    carregarEspecialidadesAdmin();


}
$(document).on('click', '.fecharDisponibilidade', function () {

    $('#modalDisponibilidade').hide();

});
$(window).click(function (e) {

    if ($(e.target).is('#modalDisponibilidade')) {

        $('#modalDisponibilidade').hide();

    }

});
function salvarAgendamento() {

    const form = document.getElementById('inserir');
    const formData = new FormData(form);

    $.ajax({

        url: '../Sessions/salvar_agendamento.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        success: function (response) {

            if (response.success) {

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message
                }).then(() => {
                    location.reload();
                });;

                $('#modalAgendamento').hide();
                form.reset();
                carregarAgendamentos();

            } else {

                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: response.message
                });

            }
        }
    });
}
function salvarDisponibilidade() {

    $.ajax({

        url: '../Sessions/salvar_disponibilidade.php',
        type: 'POST',

        data: {

            profissional_id: $('#profissional_admin').val(),

            data_inicio: $('#data_inicio_admin').val(),
            data_fim: $('#data_fim_admin').val(),

            dias: $('input[name="dias[]"]:checked')
                .map(function () {
                    return $(this).val();
                })
                .get(),

            horario: $('#horario_admin').val(),
            vagas: $('#vagas_admin').val()

        },

        dataType: 'json',

        success: function (response) {

            if (response.success) {

                Swal.fire(
                    'Sucesso',
                    response.message,
                    'success'
                );

                $('#modalDisponibilidade').hide();

                $('#formDisponibilidade')[0].reset();

                carregarDisponibilidade();

            } else {

                Swal.fire(

                    'Erro',
                    response.message,
                    'error'
                );

            }

        }

    });

}
function cancelar(id) {

    Swal.fire({
        title: 'Tem certeza?',
        text: 'Você deseja cancelar este agendamento?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Voltar'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: "../Sessions/cancelar.php",
            type: "POST",
            data: {
                agendamento_id: id
            },

            success: function (res) {

                console.log("Resposta cancelar:", res);

                Swal.fire({
                    title: 'Cancelado!',
                    text: 'O agendamento foi cancelado com sucesso.',
                    icon: 'success'
                }).then(() => {

                    console.log("Chamando carregar...");
                    carregarAgendamentos();

                });

            },

            error: function (xhr) {
                console.log(xhr.responseText);
            }

        });

    });

}
function excluirDisponibilidade(id) {

    Swal.fire({
        title: 'Tem certeza?',
        text: 'Deseja excluir esta disponibilidade?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '../Sessions/excluir_disponibilidade.php',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',

                success: function (response) {
                    if (response.success) {
                        Swal.fire('Sucesso', response.message, 'success');
                        carregarDisponibilidade(); // Certifique-se que esta função recarrega a lista
                    } else {
                        Swal.fire('Erro', response.message, 'error');
                    }
                }

            });

        }

    });

}
function carregarDisponibilidade() {

    $.ajax({

        url: '../Sessions/buscar_disponibilidade.php',
        type: 'GET',

        success: function (html) {
            if ($.fn.DataTable.isDataTable('#tabelaDisponibilidade')) {
                $('#tabelaDisponibilidade').DataTable().destroy();
            }

            $('#listarDisponibilidade').html(html);

            $('#tabelaDisponibilidade').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/pt-BR.json'
                },
                pageLength: 10,
                responsive: true
            });

        }

    });

}
//butao de voltar
function toggleMenu() {
    const menu = document.getElementById("menuDropdown");

    if (!menu) return;

    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }
}

window.onclick = function (e) {

    const menu = document.getElementById("menuDropdown");

    if (menu && !e.target.closest('.menu-config')) {
        menu.style.display = "none";
    }

}


//Aqui e o perfil
$(document).ready(function () {

    carregarPerfil();

});

function carregarPerfil() {

    $.ajax({

        url: '../Sessions/buscar_perfil.php',

        type: 'GET',

        dataType: 'json',

        success: function (dados) {

            $("#nome").val(dados.nome);

            $("#cpf").val(dados.cpf);

            $("#email").val(dados.email);

            $("#sexo").val(dados.sexo);

        }

    });

}

function editarPerfil() {
    console.log("Entrou na função editarPerfil");
    $.ajax({

        url: '../Sessions/editar_perfil.php',

        type: 'POST',

        data: {

            nome: $("#nome").val(),
            email: $("#email").val(),
            sexo: $("#sexo").val(),
            senha: $("#senha").val(),
            confirmar_senha: $("#confirmar_senha").val()

        },

        dataType: 'json',

        success: function (response) {

            if (response.success) {

                Swal.fire({
                    icon: "success",
                    title: "Sucesso",
                    text: response.message
                }).then(() => {

                    window.location.href = "../js/principal.php";

                });

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: response.message
                });

            }

        },

        error: function (xhr) {

            console.log(xhr.responseText);

        }

    });
}
