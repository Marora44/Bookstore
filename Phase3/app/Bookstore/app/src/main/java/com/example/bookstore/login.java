package com.example.bookstore;

import androidx.appcompat.app.AppCompatActivity;

import android.app.AlertDialog;
import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class login extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        Button login = (Button) findViewById(R.id.buttonLogin);
        EditText loginUser = (EditText) findViewById(R.id.loginUser);
        EditText loginPass = (EditText) findViewById(R.id.loginPass);

        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String user = loginUser.getText().toString();
                String pass = loginPass.getText().toString();
                if (TextUtils.isEmpty(user)) loginUser.setError("Please enter a username");
                else if (TextUtils.isEmpty(pass)) loginUser.setError("Please enter a password");
                else {
                    Log.d("username", user);
                    Log.d("pass", pass);
                    Response.Listener<String> loginListener = new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            Log.d("query", response);
                            try {
                                JSONObject loginResponse = new JSONObject(response);
                                if(loginResponse.getString("success") == "success") {
                                    String id = loginResponse.getString("id");
                                    Intent i = new Intent(login.this, SearchResults.class);
                                    i.putExtra("id", id);
                                    login.this.startActivity(i);
                                }
                                else{
                                    AlertDialog.Builder builder = new AlertDialog.Builder(login.this);
                                    builder.setMessage("Sign In Failed").setNegativeButton("Retry", null).create().show();
                                }
                            }
                            catch (JSONException e){
                                e.printStackTrace();
                            }
                        }
                    };
                    Response.ErrorListener err = new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            Log.d("err", error.toString());
                        }
                    };

                    StringRequest loginRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "login.php", loginListener, err) {
                        @Override
                        protected Map<String, String> getParams() {
                            Map<String, String> params = new HashMap<String, String>();
                            params.put("username", user);
                            params.put("password", pass);
                            return params;
                        }
                    };
                    RequestQueue queue = Volley.newRequestQueue(login.this);
                    queue.add(loginRequest);
                }
            }
        });

    }
}