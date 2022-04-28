package com.example.bookstore;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.content.Intent;
import android.os.Bundle;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;


public class OrderHistory extends AppCompatActivity {
    private ArrayList<OrderResults> orderList;
    private RecyclerView recyclerView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_order_history);

        //String id = getIntent().getStringExtra("id");
        Intent data = getIntent();
        try{
            JSONArray results = new JSONArray(data.getStringExtra("id"));
            //results = [{"title":"The cat In the Hat","authorID":"1","price":"50.00","author":"Dr Suess"},
            //           {"title":"Hello World!!","authorID":"1","price":"75.00","author":"Dr Suess"}]

            //Code for displaying results goes here
            for(int i = 0; i < results.length(); i++){

                //get our object, this is one book's worth of data
                JSONObject json_data = results.getJSONObject(i);

                //create new BookResults
                OrderResults resultRow = new OrderResults();

                //set that BookResults' attributes
                resultRow.id = json_data.getInt("id");
                resultRow.date = json_data.getString("authorID");
                resultRow.total = json_data.getDouble("total");
                //this is our arrayList object, we add our BookResults object to it
                orderList.add(resultRow);
            }
            // will adapt orderList:
            setAdapter();

        }
        catch (JSONException e){
            e.printStackTrace();
        }
    }
    private void setAdapter(){
        OrderAdapter adapter = new OrderAdapter(orderList);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(getApplicationContext());
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.setItemAnimator(new DefaultItemAnimator());
        recyclerView.setAdapter(adapter);
    }

}
