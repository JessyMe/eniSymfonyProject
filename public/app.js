let $sortie_ville = $("#sortie_ville")
let $token = $("#sortie_token")

$sortie_ville.change(function()
    {
        let $form = $(this).closest('form')
        let data = {}

        data[$token.attr('nom')] = $token.val()
        data[$sortie_ville.attr('name')] = $sortie_ville.val()

       $.post($form.attr('action'), data).then(function (response)
           {
               $("#sortie_lieu").replaceWith(
                   $(response).find("#sortie_lieu")
               )
           }
       )
    })

