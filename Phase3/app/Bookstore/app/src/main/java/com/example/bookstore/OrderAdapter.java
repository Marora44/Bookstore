package com.example.bookstore;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.example.bookstore.BookResults;
import com.example.bookstore.R;

import java.util.ArrayList;

public class OrderAdapter extends RecyclerView.Adapter<com.example.bookstore.OrderAdapter.ViewHolder> {

    private ArrayList<OrderResults> orderList;

    public static class ViewHolder extends RecyclerView.ViewHolder {
        static private TextView bookText;

        public ViewHolder(final View view) {
            super(view);
            //IMPLEMENT:
            bookText = (TextView) view.findViewById(R.id.searchQuery); //replace r.id.textView with the text box ID
        }
    }

    public OrderAdapter(ArrayList<OrderResults> orderList) {
        this.orderList = orderList;
    }

    // Create new views (invoked by the layout manager)
    @Override
    public com.example.bookstore.OrderAdapter.ViewHolder onCreateViewHolder(ViewGroup viewGroup, int viewType) {
        // Create a new view, which defines the UI of the list item
        View view = LayoutInflater.from(viewGroup.getContext())
                .inflate(R.layout.text_row_item, viewGroup, false);

        return new com.example.bookstore.OrderAdapter.ViewHolder(view);
    }

    // Replace the contents of a view (invoked by the layout manager)
    @Override
    public void onBindViewHolder(com.example.bookstore.OrderAdapter.ViewHolder viewHolder, final int position) {

        String date = orderList.get(position).getDate();
        Integer id = orderList.get(position).getID();
        Double total = orderList.get(position).getTotal();

        // documentation method:
        // Get element from your dataset at this position and replace the
        // contents of the view with that element
        //viewHolder.getTextView().setText(bookText[position]);

        //video method:
        com.example.bookstore.OrderAdapter.ViewHolder.bookText.setText(id + " " + date + " " + total);
    }

    // Return the size of your dataset (invoked by the layout manager)
    @Override
    public int getItemCount() {
        return orderList.size();
    }
}