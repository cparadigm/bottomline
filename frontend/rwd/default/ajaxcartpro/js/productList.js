$j(document).ready(
    function()
    {
        $j(document).on('click', 'a.button',
            function(event)
            {
                event.preventDefault();
                setLocation(this.href);
            }
        );
    }
);
