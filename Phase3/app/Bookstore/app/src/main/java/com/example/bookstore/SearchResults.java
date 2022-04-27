package com.example.bookstore;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

//IMPLEMENT LINES:
//43
//101
class BookResults {
    public String title;
    public Integer authorID;
    public Double price;
    public String authorName;

    public String getTitle(){
        return title;
    }
    public int getAuthorID(){
        return authorID;
    }
    public Double getPrice(){
        return price;
    }
    public String getAuthorName(){
        return authorName;
    }
}


public class SearchResults extends AppCompatActivity {

    private ArrayList<BookResults> booksList;
    private RecyclerView recyclerView;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_search_results);

        //IMPLEMENT:
        recyclerView = findViewById(R.id.displayQuery);

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


public class CustomAdapter extends RecyclerView.Adapter<CustomAdapter.ViewHolder> {

    private ArrayList<BookResults> booksList;

    public static class ViewHolder extends RecyclerView.ViewHolder {
        static private TextView bookText;

        public ViewHolder(final View view) {
            super(view);
            //IMPLEMENT:
            bookText = (TextView) view.findViewById(R.id.searchQuery); //replace r.id.textView with the text box ID
        }
    }

    public CustomAdapter(ArrayList<BookResults> booksList) {
        this.booksList = booksList;
    }

    // Create new views (invoked by the layout manager)
    @Override
    public ViewHolder onCreateViewHolder(ViewGroup viewGroup, int viewType) {
        // Create a new view, which defines the UI of the list item
        View view = LayoutInflater.from(viewGroup.getContext())
                .inflate(R.layout.text_row_item, viewGroup, false);

        return new ViewHolder(view);
    }

    // Replace the contents of a view (invoked by the layout manager)
    @Override
    public void onBindViewHolder(ViewHolder viewHolder, final int position) {

        String title = booksList.get(position).getTitle();
        Integer authorID = booksList.get(position).getAuthorID();
        Double price = booksList.get(position).getPrice();
        String authorName = booksList.get(position).getAuthorName();

        // documentation method:
        // Get element from your dataset at this position and replace the
        // contents of the view with that element
        //viewHolder.getTextView().setText(bookText[position]);

        //video method:
        ViewHolder.bookText.setText(title + " " + authorID + " " + price + " " +authorName);
    }

    // Return the size of your dataset (invoked by the layout manager)
    @Override
    public int getItemCount() {
        return booksList.size();
    }
}