<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test assignment</title>
    <style>
        #add_user {
            width: 30%;
        }

        #add_user input[type=text], input[type=file], select {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
        }

        #add_user input[type=submit] {
            margin: 8px 0 30px;
            font-size: larger;
        }

        .menu {
            margin-right: 15px;
        }
    </style>
</head>
<body>
<h1>PHP Developer Test assignment</h1>
<h2>for abz.agency by Alexey Morozov</h2>

<input type="text" id="users_params" class="text" value="?count=6" size="30">
<input class="menu" type="button" onclick="getUsers()" value="Get users">
<input class="menu" type="button" onclick="addUser()" value="Add user">
<input class="menu" type="button" onclick="getPositions()" value="Get positions">
<input type="text" id="user_id" class="text" value="2" size="1">
<input class="menu" type="button" onclick="getUser()" value="Get user by Id">

<div id="positions" style="display: none">
    <p><strong>Request:</strong> {{ route('positions.index') }}</p>
    <p><strong>HTTP status code:</strong> <span id="positions_response_status"></span></p>
    <pre>
        <div id="positions_data"></div>
    </pre>
    <input type="button" onclick="hidePositions()" value="Hide positions">
</div>

<div id="users" style="display: none">
    <p><strong>Request:</strong> <span id="users_request">{{ route('users.index') }}</span></p>
    <p><strong>HTTP status code:</strong> <span id="users_response_status"></span></p>
    <pre>
        <div id="users_data"></div>
    </pre>
    <p><input type="button" id="more_users" onclick="moreUsers()" value="Show more"></p>
    <input type="button" onclick="hideUsers()" value="Hide users">
</div>

<div id="add_user" style="display: none">
    <h3>Add new user</h3>
    <input type="button" onclick="getToken()" value="Get token">
    <input type="text" id="token" name="token">
    <div style="margin-top: 20px;">
        <form id="add_user_data" onsubmit="return submitUserData(event)">
            <label for="name">Name</label>
            <input type="text" name="name">
            <label for="email">Email</label>
            <input type="text" name="email">
            <label for="phone">Phone</label>
            <input type="text" name="phone">
            <label for="position_id">Position</label>
            <select name="position_id" id="position_id">
            </select>
            <label for="photo">Photo</label>
            <input type="file" name="photo">
            <input type="submit" value="Add user">
        </form>
        <div id="add_user_response" style="display: none">
            <p id="add_user_response_status_line"><strong>HTTP status code:</strong> <span
                    id="add_user_response_status"></span></p>
            <pre>
                <div id="add_user_response_data"></div>
            </pre>
        </div>
    </div>
    <input type="button" onclick="hideAddUsersForm()" value="Hide form">
</div>

<div id="user" style="display: none">
    <p><strong>Request:</strong> <span id="user_request"></span></p>
    <p><strong>HTTP status code:</strong> <span id="user_response_status"></span></p>
    <pre>
        <div id="user_data"></div>
    </pre>
    <input type="button" onclick="hideUser()" value="Hide user">
</div>

<script>
    async function getPositions() {
        let response = await fetch('{{ route('positions.index') }}');
        let positions = await response.json();

        hideAll();
        document.getElementById('positions_response_status').innerHTML = response.status;
        document.getElementById('positions_data').innerHTML = JSON.stringify(positions, null, 4);
        document.getElementById('positions').style.display = 'block';
    }

    function hidePositions() {
        document.getElementById('positions').style.display = 'none';
    }

    async function getUsers(param = null) {
        let response;
        if (param == null) {
            response = await fetch('{{ route('users.index') }}' + document.getElementById('users_params').value);
        } else {
            response = await fetch(param + '&count=6');
        }
        let users = await response.json();

        if (users['success'] === true && users['links']['next_link'] != null) {
            document.getElementById('more_users').setAttribute('onclick', 'getUsers(\'' + users['links']['next_link'] + '\')');
            document.getElementById('more_users').style.display = 'block';
        } else {
            document.getElementById('more_users').style.display = 'none';
        }

        hideAll();
        document.getElementById('users_request').innerHTML = '{{ route('users.index') }}' + document.getElementById('users_params').value;
        document.getElementById('users_response_status').innerHTML = response.status;
        document.getElementById('users_data').innerHTML = JSON.stringify(users, null, 4);
        document.getElementById('users').style.display = 'block';
    }

    function hideUsers() {
        document.getElementById('users').style.display = 'none';
    }

    async function getToken() {
        let response = await fetch('{{ route('token.create') }}');
        let token = await response.json();
        document.getElementById('token').value = token['token'];
    }

    async function addUser() {
        let response = await fetch('{{ route('positions.index') }}');
        let positions = await response.json();
        positions = positions['positions'];
        let options = '<option>Select position</option>';
        if (response.status === 200) {
            positions.forEach(function (item, i) {
                options += "<option value=" + positions[i]['id'] + ">" + positions[i]['name'] + "</option>";
            });
            document.getElementById('position_id').innerHTML = options;
        }
        hideAll();
        document.getElementById('add_user').style.display = 'block';
    }

    function hideAddUsersForm() {
        document.getElementById('add_user').style.display = 'none';
    }

    async function getUser() {
        let response;
        let url = '{{ route('users.show', 'userId') }}';

        url = url.replace('userId', document.getElementById('user_id').value);
        response = await fetch(url);

        let user = await response.json();

        hideAll();
        document.getElementById('user_request').innerHTML = url;
        document.getElementById('user_response_status').innerHTML = response.status;
        document.getElementById('user_data').innerHTML = JSON.stringify(user, null, 4);
        document.getElementById('user').style.display = 'block';
    }

    function hideUser() {
        document.getElementById('user').style.display = 'none';
    }

    function hideAll() {
        document.getElementById('positions').style.display = 'none';
        document.getElementById('users').style.display = 'none';
        document.getElementById('add_user').style.display = 'none';
        document.getElementById('user').style.display = 'none';
    }

    async function submitUserData(e) {
        e.preventDefault();

        let response = await fetch('{{ route('user.store') }}', {
            method: 'POST',
            body: new FormData(add_user_data),
            headers: {
                "Accept": "application/json",
                "Authorization": "Bearer " + document.getElementById('token').value,
            },
        });

        let result = await response.json();

        document.getElementById('add_user_response').style.display = 'block';
        document.getElementById('add_user_response_status').innerHTML = response.status;
        document.getElementById('add_user_response_data').innerHTML = JSON.stringify(result, null, 4);
    }

</script>

</body>
</html>
