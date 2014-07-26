{% extends 'clients/base.volt' %}
{% block title %}Add Policy{% endblock %}
{% block content %}
    <form role="form" method="POST">
        <h2>Add Policy</h2>
        <div class="alert">{{ flash.output() }}</div>

        <!-- Core field set -->
        <fieldset>
            <div class="form-group">
                <label for="name">Policy Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Policy Name" />
            </div>
            <div class="form-group">
                <label for="what-to-do">What To Do</label>
                <select name="what-to-do" id="what-to-do">
                    <option value="alert">Send Email Alert</option>
                    <option value="scale">Scale Server (in development)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="number-conditions">Number of Conditions</label>
                <select name="number-conditions" id="number-conditions" onchange="changeNumberOfConditions()">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </fieldset>
        <hr />
        <!-- Condition 1 -->
        <div id="condition-1">
            <strong>Condition 1</strong>
            <fieldset class="form-inline">
                <div class="form-group">
                    <select name="when[]">
                        {% for app in apps %}<option value="app-{{ app.id }}">Application: {{ app.friendly_name }}</option>{% endfor %}
                        {% for server in servers %}<option value="server-{{ server.id }}">Server: {{ server.friendly_name }}</option>{% endfor %}
                    </select>
                </div>
                <div class="form-group">
                    <select name="metric[]">
                        <option value="app-tcp-s">Application: TCP response time (s)</option>
                        <option value="app-page-s">Application: page fetch time (s)</option>
                        <option value="server-load">Server: load</option>
                        <option value="server-mem-per">Server: memory usage (%)</option>
                        <option value="sever-disk-per">Server: any disk's usage (%)</option>
                        <option value="server-iface-mbs">Server: any interface's usage (MB/s)</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="operator[]">
                        <option value="lt">is less than</option>
                        <option value="lte">is less than or equal to</option>
                        <option value="eq">is equal to</option>
                        <option value="gt">is greater than</option>
                        <option value="gte">is greater than or equal to</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="value[]" class="form-control" placeholder="Numerical Value" />
                </div>
            </fieldset>
        </div>
        <!-- Condition 2 -->
        <div id="condition-2">
            <div class="form-group">
                <select name="conditional[]">
                    <option value="or">or</option>
                    <option value="and">and</option>
                </select>
            </div><strong>Condition 2</strong>
            <fieldset class="form-inline">
                <div class="form-group">
                    <select name="when[]">
                        {% for app in apps %}<option value="app-{{ app.id }}">Application: {{ app.friendly_name }}</option>{% endfor %}
                        {% for server in servers %}<option value="server-{{ server.id }}">Server: {{ server.friendly_name }}</option>{% endfor %}
                    </select>
                </div>
                <div class="form-group">
                    <select name="metric[]">
                        <option value="app-tcp-s">Application: TCP response time (s)</option>
                        <option value="app-page-s">Application: page fetch time (s)</option>
                        <option value="server-load">Server: load</option>
                        <option value="server-mem-per">Server: memory usage (%)</option>
                        <option value="sever-disk-per">Server: any disk's usage (%)</option>
                        <option value="server-iface-mbs">Server: any interface's usage (MB/s)</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="operator[]">
                        <option value="lt">is less than</option>
                        <option value="lte">is less than or equal to</option>
                        <option value="eq">is equal to</option>
                        <option value="gt">is greater than</option>
                        <option value="gte">is greater than or equal to</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="value[]" class="form-control" placeholder="Numerical Value" />
                </div>
            </fieldset>
        </div>
        <!-- Condition 3 -->
        <div id="condition-3">
            <div class="form-group">
                <select name="conditional[]">
                    <option value="or">or</option>
                    <option value="and">and</option>
                </select>
            </div><strong>Condition 3</strong>
            <fieldset class="form-inline">
                <div class="form-group">
                    <select name="when[]">
                        {% for app in apps %}<option value="app-{{ app.id }}">Application: {{ app.friendly_name }}</option>{% endfor %}
                        {% for server in servers %}<option value="server-{{ server.id }}">Server: {{ server.friendly_name }}</option>{% endfor %}
                    </select>
                </div>
                <div class="form-group">
                    <select name="metric[]">
                        <option value="app-tcp-s">Application: TCP response time (s)</option>
                        <option value="app-page-s">Application: page fetch time (s)</option>
                        <option value="server-load">Server: load</option>
                        <option value="server-mem-per">Server: memory usage (%)</option>
                        <option value="sever-disk-per">Server: any disk's usage (%)</option>
                        <option value="server-iface-mbs">Server: any interface's usage (MB/s)</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="operator[]">
                        <option value="lt">is less than</option>
                        <option value="lte">is less than or equal to</option>
                        <option value="eq">is equal to</option>
                        <option value="gt">is greater than</option>
                        <option value="gte">is greater than or equal to</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="value[]" class="form-control" placeholder="Numerical Value" />
                </div>
            </fieldset>
        </div>

        <button class="btn btn-lg btn-primary" type="submit">Create Policy</button>
    </form>
{% endblock %}
{% block script %}
    <script>
        changeNumberOfConditions();
        function changeNumberOfConditions()
        {
            var number = document.getElementById('number-conditions').value;
            switch (parseInt(number, 10)){
                case 1:
                    document.getElementById('condition-2').style.display = 'none';
                    document.getElementById('condition-3').style.display = 'none';
                    break;
                case 2:
                    document.getElementById('condition-2').style.display = 'block';
                    document.getElementById('condition-3').style.display = 'none';
                    break;
                default:
                    document.getElementById('condition-2').style.display = 'block';
                    document.getElementById('condition-3').style.display = 'block';
                    break;
            }
        }
    </script>
{% endblock %}