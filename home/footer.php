    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
    <script>
    window.cookieconsent.initialise({
        'container': document.getElementById("content"),
        'palette':{
        'popup': {background: "#90EE90"},
        'button': {background: "#aa0000"},
        },
        'revokable':true,
        'onStatusChange': function(status) {
        console.log(this.hasConsented() ?
        'enable cookies' : 'disable cookies');
        },
        'law': {
        'regionalLaw': false,
        },
        'location': true,
        'content': {
            header: 'Cookies utilisés sur le site!',
            message: 'Ce site utilise des cookies pour améliorer votre expérience.',
            dismiss: 'Je l\'ai!',
            allow: 'Autorise les cookies',
            deny: 'Decline',
            link: 'Apprendre encore plus',
            href: 'https://www.cookiesandyou.com',
            close: '&#x274c;',
            policy: 'Cookie Policy',
            target: '_blank',
        }
    });
    </script>
    </body>
</html>