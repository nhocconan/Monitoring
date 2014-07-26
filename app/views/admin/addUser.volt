{% extends 'admin/base.volt' %}
{% block title %}Add User{% endblock %}
{% block content %}
    <form role="form" method="POST">
        <h2>Add User</h2>
        <div class="alert">{{ flash.output() }}</div>
        <div class="form-group">
            <label for="name">Name</label>
            {{ form.render('name', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            {{ form.render('email', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            {{ form.render('password', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="is_admin">Is admin?</label>
            {{ form.render('is_admin', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="monitor_servers">Number of servers to monitor</label>
            {{ form.render('monitor_servers', {'class': 'form-control'}) }}
        </div>
        <div class="form-group">
            <label for="monitor_applications">Number of applications to monitor</label>
            {{ form.render('monitor_applications', {'class': 'form-control'}) }}
        </div>

        <div class="form-group">
            <label for="send_details">Email login details to user?</label>
            {{ form.render('send_details', {'class': 'form-control'}) }}
        </div>
        <button class="btn btn-lg btn-primary" type="submit">Create User</button>
    </form>
{% endblock %}