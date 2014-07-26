{% extends 'clients/base.volt' %}
{% block title %}Applications{% endblock %}
{% block content %}
    {% set periodicities = ['Unit', 'Hour', 'Day'] %}
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                {% for app in appsList %}
                <li class="list-group-item">
                    <div class="pull-right">
                        <a href="#" onclick="deleteApplication({{ app.id }})">
                            <span class="octicon octicon-x"></span>
                        </a>
                    </div>
                    <a href="/clients/applications?aid={{ app.id }}">
                        {{ app.friendly_name }}
                    </a>
                </li>
                {% endfor %}
            </ul>
        </div>
        <div class="col-md-9">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                {% for periodicity in periodicities %}
                    <li{% if periodicity == 'Unit' %} class="active"{% endif %}><a href="#{{ periodicity }}" data-toggle="tab">{{ periodicity }}</a></li>
                {% endfor %}
            </ul>
            <br />
            <div class="tab-content">
                {% for periodicity in periodicities %}
                <div class="tab-pane{% if periodicity == 'Unit' %} active{% endif %}" id="{{ periodicity }}">
                    <!-- First stats row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">TCP Setup Time</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="{{ periodicity }}tcp" style="overflow: hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Second stats row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Page Fetch Time</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="{{ periodicity }}app" style="overflow: hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% endfor %}
            </div>
        </div>
    </div>
{% endblock %}
{% block script %}
<script>
    /*
     * Unit Metrics
     */
    // Colour generator
    var palette = new Rickshaw.Color.Palette( { scheme: 'classic9' } );

    // Unit stats
    var unitGraphs = {
        'app': {
            container: 'Unitapp',
            series: [{% for location, usage in stats['Unit']['app'] %}{
                data: [{% for data in usage %}{% for epoch, value in data %}{x: {{ epoch }}, y: {{ value }}}, {% endfor %}{% endfor %}],
                name: '{{ location }} (S)',
                color: palette.color()
            },{% endfor %}]
        },
        'tcp': {
            container: 'Unittcp',
            series: [{% for location, usage in stats['Unit']['tcp'] %}{
                data: [{% for data in usage %}{% for epoch, value in data %}{x: {{ epoch }}, y: {{ value }}}, {% endfor %}{% endfor %}],
                name: '{{ location }} (S)',
                color: palette.color()
            },{% endfor %}]
        }
    };
    // Draw graphs
    drawGraph(unitGraphs['app']['container'], unitGraphs['app']['series']);
    drawGraph(unitGraphs['tcp']['container'], unitGraphs['tcp']['series']);

    /*
     * Hour Metrics
     */
    // Colour generator
    var palette = new Rickshaw.Color.Palette( { scheme: 'classic9' } );

    // Hour stats
    var hourGraphs = {
        'app': {
            container: 'Hourapp',
            series: [{% for location, usage in stats['Hour']['app'] %}{
                data: [{% for data in usage %}{% for epoch, value in data %}{x: {{ epoch }}, y: {{ value }}}, {% endfor %}{% endfor %}],
                name: '{{ location }} (S)',
                color: palette.color()
            },{% endfor %}]
        },
        'tcp': {
            container: 'Hourtcp',
            series: [{% for location, usage in stats['Hour']['tcp'] %}{
                data: [{% for data in usage %}{% for epoch, value in data %}{x: {{ epoch }}, y: {{ value }}}, {% endfor %}{% endfor %}],
                name: '{{ location }} (S)',
                color: palette.color()
            },{% endfor %}]
        }
    };
    // Draw graphs
    drawGraph(hourGraphs['app']['container'], hourGraphs['app']['series']);
    drawGraph(hourGraphs['tcp']['container'], hourGraphs['tcp']['series']);

    /*
     * Day Metrics
     */
    // Colour generator
    var palette = new Rickshaw.Color.Palette( { scheme: 'classic9' } );

    // Day stats
    var dayGraphs = {
        'app': {
            container: 'Dayapp',
            series: [{% for location, usage in stats['Day']['app'] %}{
                data: [{% for data in usage %}{% for epoch, value in data %}{x: {{ epoch }}, y: {{ value }}}, {% endfor %}{% endfor %}],
                name: '{{ location }} (S)',
                color: palette.color()
            },{% endfor %}]
        },
        'tcp': {
            container: 'Daytcp',
            series: [{% for location, usage in stats['Day']['tcp'] %}{
                data: [{% for data in usage %}{% for epoch, value in data %}{x: {{ epoch }}, y: {{ value }}}, {% endfor %}{% endfor %}],
                name: '{{ location }} (S)',
                color: palette.color()
            },{% endfor %}]
        }
    };
    // Draw graphs
    drawGraph(dayGraphs['app']['container'], dayGraphs['app']['series']);
    drawGraph(dayGraphs['tcp']['container'], dayGraphs['tcp']['series']);

    // TODO: Move these into a JS file
    function drawGraph(container, dataSet)
    {
        // instantiate our graph!
        var graph = new Rickshaw.Graph( {
            element: document.getElementById(container),
            renderer: 'line',
            series: dataSet
        } );

        graph.render();

        var hoverDetail = new Rickshaw.Graph.HoverDetail( {
            graph: graph
        } );

        var axes = new Rickshaw.Graph.Axis.Time( {
            graph: graph
        } );
        axes.render();
    }
    function deleteApplication(aid)
    {
        if (confirm("Are you sure you want to delete application ID "+aid+"?") == true) {
            window.location.replace("/clients/deleteApplication?aid="+aid);
        }
    }
</script>
{% endblock %}