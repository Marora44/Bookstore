package com.example.bookstore;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;

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

public class OrderHistory extends AppCompatActivity {

    public static void displayResults(JSONArray res){
        //code for display here
        return;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_order_history);

        String id = getIntent().getStringExtra("id");


        Log.d("search text", id);
        Response.Listener<String> orderListener = new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.d("query", response);
                try {
                    JSONArray results = new JSONArray(response);
                    displayResults(results);
                }
                catch(JSONException e){
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

        StringRequest authRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "orderhistory.php", orderListener, err) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("id", id);
                return params;
            }
        };
        RequestQueue queue = Volley.newRequestQueue(OrderHistory.this);
        queue.add(authRequest);
    }

}
