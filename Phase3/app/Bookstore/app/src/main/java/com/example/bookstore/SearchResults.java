package com.example.bookstore;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.widget.EditText;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;

public class SearchResults extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_search_results);

        TextView tSearch = (TextView) findViewById(R.id.displayQuery);

        Intent data = getIntent();

        tSearch.setText(data.getStringExtra("search"));
        try{
            JSONArray results = new JSONArray(data.getStringExtra("results"));
            //Code for displaying results goes here
        }
        catch (JSONException e){
            e.printStackTrace();
        }
    }
}