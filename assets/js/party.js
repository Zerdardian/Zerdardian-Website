$(window).bind('beforeunload', function() {
    if(!$('#spaceevent').hasClass('none')) {
        questionEvent();
    }

    if(!$('#luckevent').hasClass('none')) {
        luckEvent();
    }

    if(!$('#unluckevent').hasClass('none')) {
        unluckEvent();
    }
})

async function moveDicePlayer(current, spaces, bg) {
    bg = bg.replace('url(', '').replace(')', '').replace(/\"/gi, "");

    var current = parseInt(current);
    var spaces = parseInt(spaces);

    var currentspace = current;

    var newspace = currentspace + spaces;


    if (newspace == currentspace) return;

    var newspacediv = $(`#mspace-${newspace}`);

    $('.player').removeClass('display');
    $('.player').addClass('disapearmove');
    await delay(1000)

    $('.player').remove();
    await delay(100);

    $(`<div class="player" style="background-image:url(${bg})"></div>`).appendTo(newspacediv);
    await delay(100)
    $('.player').addClass('appear');
    await delay(1000);
    $('.player').removeClass('appear');
    $('.player').addClass('display');
}

async function moveEventPlayer(current, spaces, type, bg) {
    bg = bg.replace('url(', '').replace(')', '').replace(/\"/gi, "");

    var current = parseInt(current);
    var spaces = parseInt(spaces);
    var type = type;

    var currentspace = current;

    if (type == 'add' || type == 1) {
        var newspace = currentspace + spaces;
    }

    if (type == 'remove' || type == 2) {
        var newspace = currentspace - spaces;
    }

    if (!newspace) return;
    if (newspace == currentspace) return;

    var newspacediv = $(`#mspace-${newspace}`);

    $('.player').removeClass('display');
    $('.player').addClass('disapearmove');
    await delay(1000)

    $('.player').remove();
    await delay(100);

    if(!newspacediv) return;

    $(`<div class="player" style="background-image:url(${bg})"></div>`).appendTo(newspacediv);
    await delay(100)
    $('.player').addClass('appear');
    await delay(1000);
    $('.player').removeClass('appear');
    $('.player').addClass('display');
}

async function dicethrow() {
    var systemdiv = $('#systemdiv');
    var currentspace = $('.player').parent();
    var current = currentspace.attr('data-space');
    var diceblock = $('.diceblock')

    var numbers = [0, 1, 2, 3, 4, 5];
    for (let index = 0; index < 20; index++) {
        const randomnumber = numbers[Math.floor(Math.random() * numbers.length)];
        $(diceblock).html(randomnumber);
        await delay(100);
    }
    await delay(100);
    // const finalnumber = numbers[Math.floor(Math.random() * numbers.length)];
    const finalnumber = 4;

    $(diceblock).html(finalnumber);
    var bg = $('.player').css('background-image');
    var currentspace = $('.player').parent();
    var current = currentspace.attr('data-space');

    var data = [{
        'function': 'do',
        'dicethrow': true,
        'dicenumber': finalnumber,
        'lastspace': current
    }]
    $.ajax({
        type: "POST",
        url: "/assets/js/ajax/mainpage.php",
        data: data[0],
        success: async function (response) {
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
                await moveDicePlayer(current, finalnumber, bg);
                await delay(1000);
                await playEvent()
            }
        }
    });
}

async function playEvent() {
    var currentspace = $('.player').parent();
    var current = currentspace.attr('data-space');

    var data = [{
        'function': 'do',
        'spaceevent': true,
        'currentspace': current
    }]
    $.ajax({
        type: "POST",
        url: "/assets/js/ajax/mainpage.php",
        data: data[0],
        success: async function (response) {
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
                if (json.type == 'BLUSPC') {
                    return;
                }

                if (json.type == 'REDSPC') {
                    var newspace = current - json.return;
                    await delay(100);
                    removeSpaces(2, newspace, json.return)
                }

                if (json.type == 'QSTSPC') {
                    $("#spaceevents").removeClass('none');
                    var file = json.file;
                    $("#spaceeventlistener").load(file);
                }
            }
        }
    });
}

async function removeSpaces(eventtype, newspace, removespaces) {
    var bg = $('.player').css('background-image');
    var currentspace = $('.player').parent();
    var current = currentspace.attr('data-space');
    switch (eventtype) {
        case 2:
            data = [{
                'function': 'do',
                'event': true,
                'typespace': 2,
                'spaces': removespaces,
                'typespaceevent': 'remove',
                'currentspace': current,
                'newspace': newspace
            }]
            $.ajax({
                type: "POST",
                url: "/assets/js/ajax/mainpage.php",
                data: data[0],
                success: async function (response) {
                    var json = JSON.parse(response)
                    await delay(100);

                    await moveEventPlayer(json.current, removespaces, 'remove', bg);
                    await message(`You have been moved back ${json.spaces} spaces!`, 2)
                }
            });
            break;
    }
}

async function questionEvent() {
    var bg = $('.player').css('background-image');
    var currentspace = $('.player').parent();
    var current = currentspace.attr('data-space');
    var questionid = $(".questionmark").attr('data-questionid');

    if (!questionid) return;

    var data = [{
        'function': 'do',
        'eventgiven': true,
        'currentspace': current,
        'eventid': questionid
    }]
    $.ajax({
        type: "POST",
        url: "/assets/js/ajax/mainpage.php",
        data: data[0],
        success: function (response) {
            var json = JSON.parse(response);

            if(json.code = 200) {
                var spaces = json.spaces
                var givetype = json.givetype

                moveEventPlayer(current, spaces, givetype, bg);

                $("#spaceevents").addClass('none');
                $("#spaceeventlistener").html('');
            }
        }
    });
}

async function luckEvent() {

}

async function unluckEvent() {

}