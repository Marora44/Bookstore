package com.example.bookstore;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

//IMPLEMENT LINES:
//43
//101
public class SearchResults extends AppCompatActivity {

    public ArrayList<BookResults> booksList = new ArrayList<>();
    public RecyclerView recyclerView;
    //public TextView temptextview;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_search_results);

        //IMPLEMENT:

    recyclerView = findViewById(R.id.searchQuery);


        //TextView tSearch = (TextView) findViewById(R.id.displayQuery);

        Intent data = getIntent();

        //tSearch.setText(data.getStringExtra("search"));

        try{
            JSONArray results = new JSONArray(data.getStringExtra("results"));
            //results = [{"title":"The cat In the Hat","authorID":"1","price":"50.00","author":"Dr Suess"},
            //           {"title":"Hello World!!","authorID":"1","price":"75.00","author":"Dr Suess"}]

            //Code for displaying results goes here
            for(int i = 0; i < results.length(); i++){

                //get our object, this is one book's worth of data
                JSONObject json_data = results.getJSONObject(i);

                //create new BookResults
                BookResults resultRow = new BookResults();

                //set that BookResults' attributes
                resultRow.title = json_data.getString("title");
                resultRow.authorID = json_data.getInt("authorID");
                resultRow.price = json_data.getDouble("price");
                resultRow.authorName = json_data.getString("author");
                //this is our arrayList object, we add our BookResults object to it
                booksList.add(resultRow);
            }
            // will adapt booksList:
            setAdapter();

        }
        catch (JSONException e){
            e.printStackTrace();
        }
    }
    private void setAdapter(){
        CustomAdapter adapter = new CustomAdapter(booksList);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(getApplicationContext());
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.setItemAnimator(new DefaultItemAnimator());
        recyclerView.setAdapter(adapter);
    }
}
