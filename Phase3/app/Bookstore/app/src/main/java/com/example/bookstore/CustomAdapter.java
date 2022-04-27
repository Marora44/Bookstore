package com.example.bookstore;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.example.bookstore.BookResults;
import com.example.bookstore.R;

import java.util.ArrayList;

public class CustomAdapter extends RecyclerView.Adapter<com.example.bookstore.CustomAdapter.ViewHolder> {

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
    public com.example.bookstore.CustomAdapter.ViewHolder onCreateViewHolder(ViewGroup viewGroup, int viewType) {
        // Create a new view, which defines the UI of the list item
        View view = LayoutInflater.from(viewGroup.getContext())
                .inflate(R.layout.text_row_item, viewGroup, false);

        return new com.example.bookstore.CustomAdapter.ViewHolder(view);
    }

    // Replace the contents of a view (invoked by the layout manager)
    @Override
    public void onBindViewHolder(com.example.bookstore.CustomAdapter.ViewHolder viewHolder, final int position) {

        String title = booksList.get(position).getTitle();
        Integer authorID = booksList.get(position).getAuthorID();
        Double price = booksList.get(position).getPrice();
        String authorName = booksList.get(position).getAuthorName();

        // documentation method:
        // Get element from your dataset at this position and replace the
        // contents of the view with that element
        //viewHolder.getTextView().setText(bookText[position]);

        //video method:
        com.example.bookstore.CustomAdapter.ViewHolder.bookText.setText(title + " " + authorID + " " + price + " " +authorName);
    }

    // Return the size of your dataset (invoked by the layout manager)
    @Override
    public int getItemCount() {
        return booksList.size();
    }
}