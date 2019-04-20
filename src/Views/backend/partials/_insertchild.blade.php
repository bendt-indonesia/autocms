<script type="text/javascript">
    function addChild(table_id, select_id, name) {
        var table = document.getElementById(table_id);
        var select = document.getElementById(select_id);

        if(table)
        {
            var id = select.value,
                option = select.options[select.selectedIndex],
                text = option.text,
                label = option.parentElement.getAttribute('label'),
                tr = document.createElement("tr"),
                td1 = document.createElement("td"),
                td2 = document.createElement("td"),
                input = document.createElement("input");

            input.type = "hidden";
            input.name = name + "[]";
            input.value = id;

            td1.innerHTML = label;
            td1.appendChild(input);

            td2.innerHTML = text;

            tr.appendChild(td1);
            tr.appendChild(td2);

            table.getElementsByTagName('tbody')[0].appendChild(tr);
        }
    }
</script>