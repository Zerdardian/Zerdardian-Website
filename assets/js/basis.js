$(document).ready(function () {
    var systemdiv = $('#systemdiv');
    var systemmessage = $('#systemmessage');
    $("#headermenubutton").click(function () {
        $('#headermenu').toggleClass('activemenu');
        setTimeout(() => {
            $('.item').toggleClass('active')
        }, 500);
    })

    // Mainpage functions
    $('.mainpage').ready(async function () {
        $('.diceblock').click(async function () {
            var data = [{
                'function': 'check',
                'dicecheck': true
            }]
            $.ajax({
                type: "POST",
                url: "/assets/js/ajax/mainpage.php",
                data: data[0],
                success: function (response) {
                    var json = JSON.parse(response);

                    if (json.code != 200) {
                        systemdiv.addClass('active');
                        systemdiv.addClass('error')

                        systemmessage.text(json.message)

                        timeout = setTimeout(() => {
                            systemdiv.removeClass('active')
                            systemdiv.removeClass('error')
                            systemmessage.text('')
                        }, 10000)
                    }

                    if (json.code == 200) {
                        dicethrow();
                    }
                }
            });
        })
    })

    // User functions

    $("#dashboard").ready(function () {
        $('input.input.changeable').change(function () {
            var settingitem = $('.dashmenu').attr('data-item');

            switch (settingitem) {
                case 'settings':
                    var id = $(this).attr('id');
                    var type = $(this).attr('type');
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    var data = [{
                        'typedata': 'input',
                        'setting': settingitem,
                        'id': id,
                        'type': type,
                        'name': name,
                        'value': value,
                    }];
                    $.ajax({
                        type: "POST",
                        url: "/assets/js/ajax/dashboard.php",
                        data: data[0],
                        success: function (response) {
                            var json = JSON.parse(response);
                            var timeout;
                            if (json.code == 200) {
                                systemdiv.addClass('active');
                                systemdiv.addClass('succes')
                                systemdiv.removeClass('error')
                                systemmessage.text(json.message)

                                if (json.id == 'displayname') {
                                    $('.displayusername').text(value)
                                }

                                clearTimeout(timeout);
                                timeout = setTimeout(() => {
                                    systemdiv.removeClass('active')
                                    systemdiv.removeClass('succes')
                                    systemmessage.text('')
                                }, 10000)
                            } else {
                                systemdiv.addClass('active');
                                systemdiv.addClass('error')
                                systemdiv.removeClass('succes')

                                systemmessage.text(json.message)

                                if (json.id == 'displayname') {
                                    $('.displayusername').text(value)
                                }

                                clearTimeout(timeout);
                                timeout = setTimeout(() => {
                                    systemdiv.removeClass('active')
                                    systemdiv.removeClass('error')
                                    systemmessage.text('')
                                }, 10000)
                            }
                        }
                    });
                    break;
                default:
                    console.log('no items');
                    break;
            }
            return;
        })

        $('input.checkbox').change(function () {
            var settingitem = $('.dashmenu').attr('data-item');

            switch (settingitem) {
                case 'settings':
                    var id = $(this).attr('id');
                    var type = $(this).attr('type');
                    var name = $(this).attr('name');
                    var value = this.checked;
                    var data = [{
                        'typedata': 'checkmark',
                        'setting': settingitem,
                        'id': id,
                        'type': type,
                        'name': name,
                        'value': value,
                    }];

                    if (id == 'allmail') {
                        if ($('#allmail').is(':checked')) {
                            $('input.checkbox').prop('checked', true);
                        } else {
                            $('input.checkbox').prop('checked', false);
                        }

                    }
                    $.ajax({
                        type: "POST",
                        url: "/assets/js/ajax/dashboard.php",
                        data: data[0],
                        success: function (response) {
                            var json = JSON.parse(response);
                            var timeout;
                            if (json.code == 200) {
                                systemdiv.addClass('active');
                                systemdiv.addClass('succes')
                                systemdiv.removeClass('error')
                                systemmessage.text(json.message)

                                if (json.id == 'displayname') {
                                    $('.displayusername').text(value)
                                }

                                clearTimeout(timeout);
                                timeout = setTimeout(() => {
                                    systemdiv.removeClass('active')
                                    systemdiv.removeClass('succes')
                                    systemmessage.text('')
                                }, 10000)
                            } else {
                                systemdiv.addClass('active');
                                systemdiv.addClass('error')
                                systemdiv.removeClass('succes')

                                systemmessage.text(json.message)

                                if (json.id == 'displayname') {
                                    $('.displayusername').text(value)
                                }

                                clearTimeout(timeout);
                                timeout = setTimeout(() => {
                                    systemdiv.removeClass('active')
                                    systemdiv.removeClass('error')
                                    systemmessage.text('')
                                }, 10000)
                            }
                        }
                    });
                    break;
                default:
                    console.log('no items');
                    break;
            }
            return;
        })
    })

    $("#connections").ready(function () {
        $('button#tleaguesearch').click(function () {
            var outputleagueuser = $('#fleagueuser');
            outputleagueuser.html('');
            var leagueuser = $('#tleagueuser').val();
            var leagueserver = $('#tleagueserver').find(":selected").val();

            if (!leagueuser) return;
            if (!leagueserver) return;
            var data = [{
                'typedata': 'getleagueuser',
                'leagueuser': leagueuser,
                'leagueserver': leagueserver,
            }];

            $.ajax({
                type: "POST",
                url: "/assets/js/ajax/connections.php",
                data: data[0],
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data['error'] == false) {
                        var profileicon = data['profileicon'];
                        var username = data['username'];
                        var level = data['level'];
                        $(`<button id='luser'>` +
                                `<div class='username'><b>${username}</b></div>` +
                                `<div class='image profilepicture'>` +
                                `<img id='profileicon'>` +
                                `</div>` +
                                `<div class="level"><span>Level ${level}</span></div>` +
                                `</button>`)
                            .appendTo(outputleagueuser)
                        $('#profileicon').attr('src', profileicon);
                        $('#luser').click(function () {
                            connectAccount('LoL', username, data['leagueid'], data)
                        })
                    }
                    if (data['error'] == true) {
                        var message = data['message'];
                        $("<div class='error'><div class='message'>" + message + "</div></div>").appendTo(outputleagueuser);
                    }
                }
            });
        })
    })
})

function canGame() {
    return "getGamepads" in navigator;
}

async function message(message, type) {
    var systemdiv = $('#systemdiv')
    var systemmessage = $('#systemessage')

    if (type = 1) {
        systemdiv.addClass('succes')
        systemdiv.removeClass('error')
    }

    if (type = 2) {
        systemdiv.addClass('error')
        systemdiv.removeClass('succes')
    }

    systemdiv.addClass('active');
    systemmessage.text(message);
    delay(10000);
    systemmessage.text('');
    systemdiv.removeClass('active')
    systemdiv.removeClass('succes')
    systemdiv.removeClass('error')
}

function connectAccount(type, name, id, data) {
    if (!type) return;
    if (!name) return;
    if (!id) return;
    if (!data) return;

    var server = data['server'];

    var div = $('div#connecting');
    div.attr('data-type', type)
    div.attr('data-name', name)
    div.attr('data-id', id)

    div.addClass('active')

    if (type == 'LoL') {
        $(`<div class="connectaccount league">` +
            `<div class="accountinfo">` +
            `<div class='image'>` +
            `<img src='${data['profileicon']}'>` +
            `</div>` +
            `<div class='text'>` +
            `<p>This is really you, ${name}? Once, you press continue, you will be redirected here.</p>` +
            `</div>` +
            `<div class='buttons'>` +
            `<button id='leaguecontinue'>Continue</button>` +
            `<button id="leaguecancel">Cancel</button>` +
            `</div>` +
            `<div id='leagueerrorhandling'></div>` +
            `</div>` +
            `</div>`).appendTo('.conLeagueAcc');

        $('#leaguecontinue').click(function () {
            var data = [{
                'typedata': 'insertleaguedata',
                'leagueuser': id,
                'leagueserver': server
            }];
            $.ajax({
                type: "POST",
                url: "/assets/js/ajax/connections.php",
                data: data[0],
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data['succes'] == true) {
                        location.reload();
                    } else {
                        $('#leagueerrohandling').html('Something went wrong! Please try again!');
                    }
                }
            });
        })
        $('#leaguecancel').click(function () {
            div.html('');
            div.attr('data-type', '')
            div.attr('data-name', '')
            div.attr('data-id', '')
            div.removeClass('active')
        })
        return;
    }
}

function delay(time) {
    return new Promise(resolve => setTimeout(resolve, time));
}