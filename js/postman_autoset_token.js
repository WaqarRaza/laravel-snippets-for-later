var jsonData = pm.response.json();
if(jsonData.status_code == 200)
    pm.environment.set("token", jsonData.data.token);
