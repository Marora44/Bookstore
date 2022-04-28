package com.example.bookstore;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

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

        RecyclerView recyclerView = (RecyclerView) findViewById(R.id.searchResults);
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getApplicationContext());
        recyclerView.setLayoutManager(linearLayoutManager);

        
        JSONArray results = null;


        try{
            results = new JSONArray(data.getStringExtra("results"));
        }
        catch (JSONException e){
            e.printStackTrace();
        }
        SearchAdapter searchAdapter = new SearchAdapter(SearchResults.this,results);
        recyclerView.setAdapter(searchAdapter);
    }
}