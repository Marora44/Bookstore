package com.example.bookstore;

import androidx.appcompat.app.AppCompatActivity;

import android.app.DownloadManager;
import android.content.Intent;
import android.os.Bundle;
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

import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {

    EditText etSearch;
    Button searchAuthor, searchTitle, login;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        searchAuthor = (Button) findViewById(R.id.searchAuthor);
        searchTitle = (Button) findViewById(R.id.searchTitle);
        login = (Button) findViewById(R.id.Login);
        etSearch = (EditText) findViewById(R.id.searchQuery);



        searchAuthor.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String search = etSearch.getText().toString();
                Log.d("search text",search);
                Response.Listener<String> authListener = new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("query",response);
                        Intent i = new Intent(MainActivity.this, SearchResults.class);
                        i.putExtra("results",response);
                        i.putExtra("search",search);
                        MainActivity.this.startActivity(i);
                    }
                };
                Response.ErrorListener err = new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.d("err", error.toString());
                    }
                };

                StringRequest authRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "searchauthor.php",authListener,err) {
                    @Override
                    protected Map<String, String> getParams() {
                        Map<String, String> params = new HashMap<String, String>();
                        params.put("search", search);
                        return params;
                    }
                };
                RequestQueue queue = Volley.newRequestQueue(MainActivity.this);
                queue.add(authRequest);
            }
        });

        searchTitle.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String search = etSearch.getText().toString();
                Log.d("search text",search);
                Response.Listener<String> titleListener = new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("query",response);
                        Intent i = new Intent(MainActivity.this, SearchResults.class);
                        i.putExtra("results",response);
                        i.putExtra("search",search);
                        MainActivity.this.startActivity(i);
                    }
                };
                Response.ErrorListener err = new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.d("err", error.toString());
                    }
                };

                StringRequest titleRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "searchtitle.php",titleListener,err) {
                    @Override
                    protected Map<String, String> getParams() {
                        Map<String, String> params = new HashMap<String, String>();
                        params.put("search", search);
                        return params;
                    }
                };
                RequestQueue queue = Volley.newRequestQueue(MainActivity.this);
                queue.add(titleRequest);
            }
        });

        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(MainActivity.this,login.class);
                MainActivity.this.startActivity(i);
            }
        });


    }
}