{% extends 'admin/base.volt' %}
{% block title %}Email Users{% endblock %}
{% block content %}
    <form role="form" method="POST">
        <h2>Email Users</h2>
        <div class="alert">{{ flash.output() }}</div>
        <div class="form-group">
            <label for="subject">Subject</label>
            {{ form.render('subject', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            {{ form.render('message', {'class': 'form-control'}) }}
        </div>
        <button class="btn btn-lg btn-primary" type="submit">Create Server</button>
    </form>
{% endblock %}