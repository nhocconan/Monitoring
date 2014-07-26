{% extends 'admin/base.volt' %}
{% block title %}Add Application{% endblock %}
{% block content %}
    <form role="form" method="POST">
        <h2>Add Application</h2>
        <div class="alert">{{ flash.output() }}</div>
        <div class="form-group">
            <label for="url">Application Test Page URL</label>
            {{ form.render('url', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="content">A Healthy Response Contains</label>
            {{ form.render('content', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="owner">Owner</label>
            {{ form.render('owner', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="server">Server</label>
            {{ form.render('server', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="type">Application Type</label>
            {{ form.render('type', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="type">Friendly Name</label>
            {{ form.render('friendly_name', {'class': 'form-control'}) }}
        </div>
        <button class="btn btn-lg btn-primary" type="submit">Create Application</button>
    </form>
{% endblock %}